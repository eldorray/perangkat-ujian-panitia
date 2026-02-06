<?php

namespace App\Livewire\Admin;

use App\Models\KegiatanUjian;
use App\Models\Kelas;
use App\Models\PenempatanRuangUjian;
use App\Models\SchoolSetting;
use App\Models\Siswa;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Kartu Peserta Ujian')]
class KartuPesertaManagement extends Component
{
    public KegiatanUjian $kegiatanUjian;
    
    public string $selectedTingkat = '';
    public string $selectedKelas = '';
    public string $search = '';
    public array $selectedSiswa = [];
    public bool $selectAll = false;
    public bool $showPreviewModal = false;
    public ?Siswa $previewSiswa = null;

    public function mount(int $kegiatanUjianId): void
    {
        $this->kegiatanUjian = KegiatanUjian::with('tahunAjaran')->findOrFail($kegiatanUjianId);
    }

    public function updatedSelectedTingkat(): void
    {
        $this->selectedKelas = '';
        $this->selectedSiswa = [];
        $this->selectAll = false;
    }

    public function updatedSelectAll(): void
    {
        if ($this->selectAll) {
            $this->selectedSiswa = $this->getSiswas()->pluck('id')->map(fn($id) => (string) $id)->toArray();
        } else {
            $this->selectedSiswa = [];
        }
    }

    public function toggleSiswa(int $siswaId): void
    {
        $siswaIdStr = (string) $siswaId;
        if (in_array($siswaIdStr, $this->selectedSiswa)) {
            $this->selectedSiswa = array_values(array_diff($this->selectedSiswa, [$siswaIdStr]));
        } else {
            $this->selectedSiswa[] = $siswaIdStr;
        }
        
        // Update selectAll status
        $totalSiswa = $this->getSiswas()->count();
        $this->selectAll = count($this->selectedSiswa) === $totalSiswa && $totalSiswa > 0;
    }

    public function previewKartu(int $siswaId): void
    {
        $this->previewSiswa = Siswa::find($siswaId);
        $this->showPreviewModal = true;
    }

    public function closePreview(): void
    {
        $this->showPreviewModal = false;
        $this->previewSiswa = null;
    }

    public function getKelasList(): Collection
    {
        return Kelas::query()
            ->when($this->selectedTingkat, fn($q) => $q->where('tingkat', $this->selectedTingkat))
            ->orderBy('tingkat')
            ->orderBy('nama')
            ->get();
    }

    public function getSiswas(): Collection
    {
        return Siswa::query()
            ->when($this->search, fn($q) => $q->where('nama_lengkap', 'like', "%{$this->search}%")
                ->orWhere('nisn', 'like', "%{$this->search}%"))
            ->when($this->selectedTingkat, function($q) {
                // Filter by tingkat pattern in tingkat_rombel
                if ($this->selectedTingkat === 'MI') {
                    $q->where(function($query) {
                        $query->where('tingkat_rombel', 'like', '%1%')
                              ->orWhere('tingkat_rombel', 'like', '%2%')
                              ->orWhere('tingkat_rombel', 'like', '%3%')
                              ->orWhere('tingkat_rombel', 'like', '%4%')
                              ->orWhere('tingkat_rombel', 'like', '%5%')
                              ->orWhere('tingkat_rombel', 'like', '%6%');
                    });
                } elseif ($this->selectedTingkat === 'SMP') {
                    $q->where(function($query) {
                        $query->where('tingkat_rombel', 'like', '%7%')
                              ->orWhere('tingkat_rombel', 'like', '%8%')
                              ->orWhere('tingkat_rombel', 'like', '%9%');
                    });
                }
            })
            ->when($this->selectedKelas, fn($q) => $q->where('tingkat_rombel', 'like', "%{$this->selectedKelas}%"))
            ->where('status', 'Aktif')
            ->orderBy('nama_lengkap')
            ->get();
    }

    public function generateNomorPeserta(): void
    {
        $year = date('y'); // 2 digit year
        $kodeSekolah = '70'; // School code
        
        // Get all placements for this kegiatan that don't have nomor_peserta yet
        $placements = PenempatanRuangUjian::where('kegiatan_ujian_id', $this->kegiatanUjian->id)
            ->whereNull('nomor_peserta')
            ->orderBy('ruang_ujian_id')
            ->orderBy('nomor_urut')
            ->get();
        
        // Get current max nomor urut for this year
        $maxNomor = PenempatanRuangUjian::where('kegiatan_ujian_id', $this->kegiatanUjian->id)
            ->whereNotNull('nomor_peserta')
            ->max('nomor_peserta');
        
        // Extract last 4 digits from format YY-CC-0001
        $startUrut = $maxNomor ? (int) substr($maxNomor, -4) + 1 : 1;
        
        foreach ($placements as $index => $placement) {
            $nomorUrut = str_pad($startUrut + $index, 4, '0', STR_PAD_LEFT);
            $placement->nomor_peserta = $year . '-' . $kodeSekolah . '-' . $nomorUrut;
            $placement->save();
        }
        
        $count = $placements->count();
        session()->flash('success', "Berhasil generate {$count} nomor peserta.");
    }

    public function getPrintUrl(): string
    {
        if (empty($this->selectedSiswa)) {
            return '#';
        }
        
        return route('admin.kegiatan-ujian.kartu-peserta.print', [
            'kegiatanUjianId' => $this->kegiatanUjian->id,
            'siswa_ids' => implode(',', $this->selectedSiswa),
        ]);
    }

    public function render(): View
    {
        $siswas = $this->getSiswas();
        $kelasList = $this->getKelasList();
        $schoolSettings = SchoolSetting::getAllSettings();
        
        return view('livewire.admin.kartu-peserta-management', compact('siswas', 'kelasList', 'schoolSettings'));
    }
}
