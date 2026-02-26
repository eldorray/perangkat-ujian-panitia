<?php

namespace App\Livewire\Admin;

use App\Models\JadwalUjian;
use App\Models\Kelas;
use App\Models\KegiatanUjian;
use App\Models\PenempatanRuangUjian;
use App\Models\RuangUjian;
use App\Models\SchoolSetting;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Label Amplop Soal')]
class LabelAmplopSoal extends Component
{
    public KegiatanUjian $kegiatanUjian;

    // Selected kelas nama (e.g. "1A", "2B")
    public string $selectedKelas = '';

    // Jadwal data: jadwal_id => ['jumlah_soal' => x, 'jumlah_cadangan' => y]
    public array $jadwalData = [];

    public function mount(int $id): void
    {
        $this->kegiatanUjian = KegiatanUjian::with('tahunAjaran')->findOrFail($id);

        // Auto-select first kelas
        $kelasList = $this->getKelasList();
        if ($kelasList->isNotEmpty()) {
            $this->selectedKelas = $kelasList->first()->nama;
        }

        $this->loadJadwalData();
    }

    public function selectKelas(string $kelasNama): void
    {
        $this->selectedKelas = $kelasNama;
        $this->loadJadwalData();
    }

    protected function loadJadwalData(): void
    {
        $jadwals = $this->getFilteredJadwals();

        // Get student count for the selected kelas (from penempatan data)
        $siswaCount = PenempatanRuangUjian::where('kegiatan_ujian_id', $this->kegiatanUjian->id)
            ->where('kelas_nama', $this->selectedKelas)
            ->count();

        $this->jadwalData = [];
        foreach ($jadwals as $jadwal) {
            $this->jadwalData[$jadwal->id] = [
                'jumlah_soal' => $jadwal->jumlah_soal ?? ($siswaCount > 0 ? $siswaCount : 0),
                'jumlah_cadangan' => $jadwal->jumlah_cadangan ?? 0,
            ];
        }
    }

    protected function getKelasList()
    {
        return Kelas::orderBy('tingkat')->orderBy('nama')->get();
    }

    /**
     * Get jadwals that apply to the selected kelas.
     * Match kelompok_kelas (e.g. "Kelas I") to kelas tingkat (e.g. "I").
     */
    protected function getFilteredJadwals()
    {
        $kelas = Kelas::where('nama', $this->selectedKelas)->first();
        if (!$kelas) {
            return collect();
        }

        $tingkat = $kelas->tingkat; // e.g. "I", "II", "III"

        return JadwalUjian::where('kegiatan_ujian_id', $this->kegiatanUjian->id)
            ->where(function ($q) use ($tingkat) {
                $q->where('kelompok_kelas', "Kelas {$tingkat}")
                    ->orWhereNull('kelompok_kelas')
                    ->orWhere('kelompok_kelas', '');
            })
            ->orderBy('tanggal')
            ->orderBy('sort_order')
            ->orderBy('jam_mulai')
            ->get();
    }

    public function save(): void
    {
        foreach ($this->jadwalData as $jadwalId => $data) {
            JadwalUjian::where('id', $jadwalId)->update([
                'jumlah_soal' => max(0, (int) ($data['jumlah_soal'] ?? 0)),
                'jumlah_cadangan' => max(0, (int) ($data['jumlah_cadangan'] ?? 0)),
            ]);
        }

        session()->flash('message', 'Data jumlah soal berhasil disimpan.');
    }

    public function render(): View
    {
        $kelasList = $this->getKelasList();
        $jadwals = $this->getFilteredJadwals();

        // Get room info for the selected kelas from penempatan data
        $ruangInfo = PenempatanRuangUjian::where('kegiatan_ujian_id', $this->kegiatanUjian->id)
            ->where('kelas_nama', $this->selectedKelas)
            ->selectRaw('ruang_ujian_id, COUNT(*) as jumlah_siswa')
            ->groupBy('ruang_ujian_id')
            ->get();

        $ruangIds = $ruangInfo->pluck('ruang_ujian_id');
        $siswaPerRuang = $ruangInfo->pluck('jumlah_siswa', 'ruang_ujian_id');

        $ruangList = $ruangIds->isNotEmpty()
            ? RuangUjian::whereIn('id', $ruangIds)->orderBy('kode')->get()
            : collect();

        $totalSiswa = $siswaPerRuang->sum();

        $schoolSettings = SchoolSetting::getAllSettings();

        return view('livewire.admin.label-amplop-soal', compact(
            'kelasList',
            'jadwals',
            'ruangList',
            'siswaPerRuang',
            'totalSiswa',
            'schoolSettings'
        ));
    }
}
