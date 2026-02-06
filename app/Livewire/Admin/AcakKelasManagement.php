<?php

namespace App\Livewire\Admin;

use App\Models\Kelas;
use App\Models\KegiatanUjian;
use App\Models\PasanganKelasUjian;
use App\Models\PenempatanRuangUjian;
use App\Models\RuangUjian;
use App\Models\Siswa;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Acak Kelas')]
class AcakKelasManagement extends Component
{
    public KegiatanUjian $kegiatanUjian;
    
    // Form properties
    public string $kelas_a = '';
    public string $kelas_b = '';
    
    // Modal states
    public bool $showFormModal = false;
    public bool $showResultModal = false;
    public bool $showConfirmGenerateModal = false;
    public bool $showConfirmResetModal = false;
    
    // Selected pasangan for viewing result
    public ?int $selectedPasanganId = null;

    public function mount(int $id): void
    {
        $this->kegiatanUjian = KegiatanUjian::with('tahunAjaran')->findOrFail($id);
    }

    public function render(): View
    {
        $pasangans = PasanganKelasUjian::where('kegiatan_ujian_id', $this->kegiatanUjian->id)
            ->withCount('penempatans')
            ->get();
        
        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama')->get();
        $ruangList = RuangUjian::orderBy('kode')->get();
        
        // Get room occupancy info for this kegiatan ujian
        $ruangWithOccupancy = $ruangList->map(function ($ruang) {
            // Get all penempatans for this room in this kegiatan
            $penempatans = PenempatanRuangUjian::where('kegiatan_ujian_id', $this->kegiatanUjian->id)
                ->where('ruang_ujian_id', $ruang->id)
                ->get();
            
            $terisi = $penempatans->count();
            
            // Get unique classes in this room
            $kelasInRoom = $penempatans->pluck('kelas_nama')->filter()->unique()->values()->toArray();
            $asalKelas = $penempatans->pluck('asal_kelas')->filter()->unique()->values()->toArray();
            $allKelas = collect(array_merge($kelasInRoom, $asalKelas))->unique()->values()->toArray();
            
            return [
                'id' => $ruang->id,
                'kode' => $ruang->kode,
                'nama' => $ruang->nama,
                'kapasitas' => $ruang->kapasitas,
                'terisi' => $terisi,
                'kelas_list' => $allKelas,
            ];
        });
        
        // Get penempatan grouped by ruang if viewing result
        $penempatanByRuang = collect();
        if ($this->selectedPasanganId) {
            $penempatanByRuang = PenempatanRuangUjian::where('pasangan_kelas_ujian_id', $this->selectedPasanganId)
                ->with(['siswa', 'ruangUjian'])
                ->orderBy('ruang_ujian_id')
                ->orderBy('nomor_urut')
                ->get()
                ->groupBy('ruang_ujian_id');
        }

        return view('livewire.admin.acak-kelas-management', compact(
            'pasangans',
            'kelasList',
            'ruangWithOccupancy',
            'penempatanByRuang'
        ));
    }

    public function openFormModal(): void
    {
        $this->reset(['kelas_a', 'kelas_b']);
        $this->showFormModal = true;
    }

    public function closeFormModal(): void
    {
        $this->showFormModal = false;
    }

    public function savePasangan(): void
    {
        $this->validate([
            'kelas_a' => 'required|string',
            'kelas_b' => 'required|string|different:kelas_a',
        ], [
            'kelas_a.required' => 'Kelas A harus dipilih',
            'kelas_b.required' => 'Kelas B harus dipilih',
            'kelas_b.different' => 'Kelas B harus berbeda dengan Kelas A',
        ]);

        // Check if pair already exists
        $exists = PasanganKelasUjian::where('kegiatan_ujian_id', $this->kegiatanUjian->id)
            ->where(function ($q) {
                $q->where(function ($q2) {
                    $q2->where('kelas_a_nama', $this->kelas_a)
                       ->where('kelas_b_nama', $this->kelas_b);
                })->orWhere(function ($q2) {
                    $q2->where('kelas_a_nama', $this->kelas_b)
                       ->where('kelas_b_nama', $this->kelas_a);
                });
            })
            ->exists();

        if ($exists) {
            $this->addError('kelas_b', 'Pasangan kelas ini sudah ada');
            return;
        }

        PasanganKelasUjian::create([
            'kegiatan_ujian_id' => $this->kegiatanUjian->id,
            'kelas_a_nama' => $this->kelas_a,
            'kelas_b_nama' => $this->kelas_b,
        ]);

        $this->closeFormModal();
        session()->flash('message', 'Pasangan kelas berhasil ditambahkan');
    }

    public function confirmGenerate(int $pasanganId): void
    {
        $this->selectedPasanganId = $pasanganId;
        $this->showConfirmGenerateModal = true;
    }

    public function generatePenempatan(): void
    {
        $pasangan = PasanganKelasUjian::findOrFail($this->selectedPasanganId);
        
        // Delete existing penempatan for this pasangan
        PenempatanRuangUjian::where('pasangan_kelas_ujian_id', $pasangan->id)->delete();
        
        // Get siswa from both classes
        $siswaA = $pasangan->getSiswaKelasA()->shuffle();
        $siswaB = $pasangan->getSiswaKelasB()->shuffle();
        
        // Get all ruang
        $allRuang = RuangUjian::orderBy('kode')->get();
        
        if ($allRuang->isEmpty()) {
            session()->flash('error', 'Tidak ada ruang ujian tersedia. Silakan tambahkan ruang ujian terlebih dahulu.');
            $this->showConfirmGenerateModal = false;
            return;
        }
        
        // Get rooms that are already occupied by OTHER pasangan in this kegiatan
        $occupiedRuangIds = PenempatanRuangUjian::where('kegiatan_ujian_id', $this->kegiatanUjian->id)
            ->where('pasangan_kelas_ujian_id', '!=', $pasangan->id)
            ->distinct()
            ->pluck('ruang_ujian_id')
            ->toArray();
        
        // Also check rooms occupied by per-kelas mode
        $occupiedByPerKelas = PenempatanRuangUjian::where('kegiatan_ujian_id', $this->kegiatanUjian->id)
            ->whereNull('pasangan_kelas_ujian_id')
            ->distinct()
            ->pluck('ruang_ujian_id')
            ->toArray();
        
        $allOccupiedIds = array_unique(array_merge($occupiedRuangIds, $occupiedByPerKelas));
        
        // Filter to get only available (empty) rooms
        $availableRuang = $allRuang->filter(function ($ruang) use ($allOccupiedIds) {
            return !in_array($ruang->id, $allOccupiedIds);
        });
        
        if ($availableRuang->isEmpty()) {
            session()->flash('error', 'Semua ruang ujian sudah terisi oleh pasangan kelas lain. Silakan reset penempatan lain atau tambah ruang baru.');
            $this->showConfirmGenerateModal = false;
            return;
        }
        
        $totalSiswa = $siswaA->count() + $siswaB->count();
        $totalKapasitasAvailable = $availableRuang->sum('kapasitas');
        
        if ($totalSiswa > $totalKapasitasAvailable) {
            session()->flash('error', "Total siswa ({$totalSiswa}) melebihi kapasitas ruang yang tersedia ({$totalKapasitasAvailable}). Silakan reset penempatan lain atau tambah ruang.");
            $this->showConfirmGenerateModal = false;
            return;
        }
        
        // Distribute students to available rooms
        $indexA = 0;
        $indexB = 0;
        
        foreach ($availableRuang as $ruang) {
            $kapasitas = $ruang->kapasitas;
            $halfKapasitas = (int) floor($kapasitas / 2);
            
            $nomorUrut = 1;
            
            // Take half from class A
            $siswaFromA = $siswaA->slice($indexA, $halfKapasitas);
            foreach ($siswaFromA as $siswa) {
                PenempatanRuangUjian::create([
                    'kegiatan_ujian_id' => $this->kegiatanUjian->id,
                    'pasangan_kelas_ujian_id' => $pasangan->id,
                    'ruang_ujian_id' => $ruang->id,
                    'siswa_id' => $siswa->id,
                    'nomor_urut' => $nomorUrut++,
                    'asal_kelas' => $pasangan->kelas_a_nama,
                ]);
            }
            $indexA += $siswaFromA->count();
            
            // Take half from class B
            $siswaFromB = $siswaB->slice($indexB, $halfKapasitas);
            foreach ($siswaFromB as $siswa) {
                PenempatanRuangUjian::create([
                    'kegiatan_ujian_id' => $this->kegiatanUjian->id,
                    'pasangan_kelas_ujian_id' => $pasangan->id,
                    'ruang_ujian_id' => $ruang->id,
                    'siswa_id' => $siswa->id,
                    'nomor_urut' => $nomorUrut++,
                    'asal_kelas' => $pasangan->kelas_b_nama,
                ]);
            }
            $indexB += $siswaFromB->count();
            
            // Check if all students placed
            if ($indexA >= $siswaA->count() && $indexB >= $siswaB->count()) {
                break;
            }
        }
        
        $this->showConfirmGenerateModal = false;
        session()->flash('message', 'Penempatan siswa berhasil di-generate');
    }

    public function viewResult(int $pasanganId): void
    {
        $this->selectedPasanganId = $pasanganId;
        $this->showResultModal = true;
    }

    public function closeResultModal(): void
    {
        $this->selectedPasanganId = null;
        $this->showResultModal = false;
    }

    public function confirmReset(int $pasanganId): void
    {
        $this->selectedPasanganId = $pasanganId;
        $this->showConfirmResetModal = true;
    }

    public function resetPenempatan(): void
    {
        PenempatanRuangUjian::where('pasangan_kelas_ujian_id', $this->selectedPasanganId)->delete();
        
        $this->showConfirmResetModal = false;
        $this->selectedPasanganId = null;
        session()->flash('message', 'Penempatan berhasil direset');
    }

    public function deletePasangan(int $id): void
    {
        $pasangan = PasanganKelasUjian::findOrFail($id);
        $pasangan->delete(); // This will also cascade delete penempatans
        
        session()->flash('message', 'Pasangan kelas berhasil dihapus');
    }
}
