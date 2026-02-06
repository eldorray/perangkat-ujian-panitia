<?php

namespace App\Livewire\Admin;

use App\Models\Kelas;
use App\Models\KegiatanUjian;
use App\Models\PenempatanRuangUjian;
use App\Models\RuangUjian;
use App\Models\Siswa;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Penempatan Per Kelas')]
class PenempatanPerKelasManagement extends Component
{
    public KegiatanUjian $kegiatanUjian;
    
    // Form properties
    public string $selectedKelas = '';
    
    // Modal states
    public bool $showFormModal = false;
    public bool $showConfirmGenerateModal = false;
    public bool $showConfirmResetModal = false;
    
    // Selected kelas for actions
    public ?string $actionKelasNama = null;

    public function mount(int $id): void
    {
        $this->kegiatanUjian = KegiatanUjian::with('tahunAjaran')->findOrFail($id);
    }

    public function render(): View
    {
        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama')->get();
        $ruangList = RuangUjian::orderBy('kode')->get();
        
        // Get placement status per kelas
        $kelasWithStatus = $kelasList->map(function ($kelas) {
            $count = PenempatanRuangUjian::where('kegiatan_ujian_id', $this->kegiatanUjian->id)
                ->where('kelas_nama', $kelas->nama)
                ->whereNull('pasangan_kelas_ujian_id')
                ->count();
            
            return [
                'id' => $kelas->id,
                'nama' => $kelas->nama,
                'tingkat' => $kelas->tingkat,
                'penempatans_count' => $count,
            ];
        });
        
        // Get room occupancy info for this kegiatan ujian
        $ruangWithOccupancy = $ruangList->map(function ($ruang) {
            // Get all penempatans for this room in this kegiatan (both acak kelas and per kelas)
            $penempatans = PenempatanRuangUjian::where('kegiatan_ujian_id', $this->kegiatanUjian->id)
                ->where('ruang_ujian_id', $ruang->id)
                ->get();
            
            $terisi = $penempatans->count();
            
            // Get unique classes in this room
            $kelasInRoom = $penempatans->pluck('kelas_nama')->filter()->unique()->values()->toArray();
            // Also check asal_kelas for acak kelas mode
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

        return view('livewire.admin.penempatan-per-kelas-management', compact(
            'kelasWithStatus',
            'ruangWithOccupancy'
        ));
    }

    public function openFormModal(): void
    {
        $this->reset(['selectedKelas']);
        $this->showFormModal = true;
    }

    public function closeFormModal(): void
    {
        $this->showFormModal = false;
    }

    public function confirmGenerate(): void
    {
        $this->validate([
            'selectedKelas' => 'required|string',
        ], [
            'selectedKelas.required' => 'Kelas harus dipilih',
        ]);

        $this->actionKelasNama = $this->selectedKelas;
        $this->showFormModal = false;
        $this->showConfirmGenerateModal = true;
    }

    public function generatePenempatan(): void
    {
        if (!$this->actionKelasNama) {
            return;
        }

        // Delete existing penempatan for this kelas (per-kelas mode)
        PenempatanRuangUjian::where('kegiatan_ujian_id', $this->kegiatanUjian->id)
            ->where('kelas_nama', $this->actionKelasNama)
            ->whereNull('pasangan_kelas_ujian_id')
            ->delete();
        
        // Get siswa from the class, ordered by name A-Z
        $siswaList = Siswa::where('tingkat_rombel', 'like', "%{$this->actionKelasNama}%")
            ->orderBy('nama_lengkap')
            ->get();
        
        // Get all ruang
        $allRuang = RuangUjian::orderBy('kode')->get();
        
        if ($allRuang->isEmpty()) {
            session()->flash('error', 'Tidak ada ruang ujian tersedia. Silakan tambahkan ruang ujian terlebih dahulu.');
            $this->showConfirmGenerateModal = false;
            return;
        }
        
        if ($siswaList->isEmpty()) {
            session()->flash('error', "Tidak ada siswa di kelas {$this->actionKelasNama}.");
            $this->showConfirmGenerateModal = false;
            return;
        }
        
        // Get rooms that are already occupied by OTHER classes in this kegiatan
        $occupiedRuangIds = PenempatanRuangUjian::where('kegiatan_ujian_id', $this->kegiatanUjian->id)
            ->where('kelas_nama', '!=', $this->actionKelasNama)
            ->distinct()
            ->pluck('ruang_ujian_id')
            ->toArray();
        
        // Filter to get only available (empty) rooms
        $availableRuang = $allRuang->filter(function ($ruang) use ($occupiedRuangIds) {
            return !in_array($ruang->id, $occupiedRuangIds);
        });
        
        if ($availableRuang->isEmpty()) {
            session()->flash('error', 'Semua ruang ujian sudah terisi oleh kelas lain. Silakan reset penempatan kelas lain atau tambah ruang baru.');
            $this->showConfirmGenerateModal = false;
            return;
        }
        
        $totalSiswa = $siswaList->count();
        $totalKapasitasAvailable = $availableRuang->sum('kapasitas');
        
        if ($totalSiswa > $totalKapasitasAvailable) {
            session()->flash('error', "Total siswa ({$totalSiswa}) melebihi kapasitas ruang yang tersedia ({$totalKapasitasAvailable}). Silakan reset penempatan kelas lain atau tambah ruang.");
            $this->showConfirmGenerateModal = false;
            return;
        }
        
        // Distribute students to available rooms sequentially (not randomized)
        $siswaIndex = 0;
        
        foreach ($availableRuang as $ruang) {
            $kapasitas = $ruang->kapasitas;
            $nomorUrut = 1;
            
            for ($i = 0; $i < $kapasitas && $siswaIndex < $totalSiswa; $i++) {
                $siswa = $siswaList[$siswaIndex];
                
                PenempatanRuangUjian::create([
                    'kegiatan_ujian_id' => $this->kegiatanUjian->id,
                    'pasangan_kelas_ujian_id' => null, // No pasangan for per-kelas mode
                    'ruang_ujian_id' => $ruang->id,
                    'siswa_id' => $siswa->id,
                    'nomor_urut' => $nomorUrut++,
                    'asal_kelas' => $this->actionKelasNama,
                    'kelas_nama' => $this->actionKelasNama,
                ]);
                
                $siswaIndex++;
            }
            
            if ($siswaIndex >= $totalSiswa) {
                break;
            }
        }
        
        $this->showConfirmGenerateModal = false;
        $this->actionKelasNama = null;
        session()->flash('message', 'Penempatan siswa berhasil di-generate');
    }

    public function confirmReset(string $kelasNama): void
    {
        $this->actionKelasNama = $kelasNama;
        $this->showConfirmResetModal = true;
    }

    public function resetPenempatan(): void
    {
        PenempatanRuangUjian::where('kegiatan_ujian_id', $this->kegiatanUjian->id)
            ->where('kelas_nama', $this->actionKelasNama)
            ->whereNull('pasangan_kelas_ujian_id')
            ->delete();
        
        $this->showConfirmResetModal = false;
        $this->actionKelasNama = null;
        session()->flash('message', 'Penempatan berhasil direset');
    }
}
