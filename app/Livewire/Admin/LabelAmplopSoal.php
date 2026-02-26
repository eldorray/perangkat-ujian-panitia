<?php

namespace App\Livewire\Admin;

use App\Models\JadwalUjian;
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

    // Selected jadwal for printing
    public ?int $selectedJadwalId = null;

    // Cadangan soal
    public int $jumlahCadangan = 2;

    public function mount(int $id): void
    {
        $this->kegiatanUjian = KegiatanUjian::with('tahunAjaran')->findOrFail($id);

        // Load saved settings
        $settings = session("label_amplop_settings_{$id}", []);
        $this->jumlahCadangan = $settings['jumlahCadangan'] ?? 2;
    }

    public function updatedJumlahCadangan(): void
    {
        $this->saveSettings();
    }

    protected function saveSettings(): void
    {
        session(["label_amplop_settings_{$this->kegiatanUjian->id}" => [
            'jumlahCadangan' => $this->jumlahCadangan,
        ]]);
    }

    public function render(): View
    {
        $jadwalList = JadwalUjian::where('kegiatan_ujian_id', $this->kegiatanUjian->id)
            ->orderBy('tanggal')
            ->orderBy('jam_mulai')
            ->get();

        $selectedJadwal = $this->selectedJadwalId
            ? JadwalUjian::find($this->selectedJadwalId)
            : null;

        // Get student count per room from penempatan data
        $siswaPerRuang = PenempatanRuangUjian::where('kegiatan_ujian_id', $this->kegiatanUjian->id)
            ->selectRaw('ruang_ujian_id, COUNT(*) as jumlah_siswa')
            ->groupBy('ruang_ujian_id')
            ->pluck('jumlah_siswa', 'ruang_ujian_id');

        $hasPenempatan = $siswaPerRuang->isNotEmpty();

        if ($hasPenempatan) {
            // Only get rooms that have students placed
            $ruangList = RuangUjian::whereIn('id', $siswaPerRuang->keys())
                ->orderBy('kode')
                ->get()
                ->map(function ($ruang) use ($siswaPerRuang) {
                    $ruang->jumlah_siswa = $siswaPerRuang[$ruang->id] ?? 0;
                    return $ruang;
                });
        } else {
            // Fallback: all rooms with kapasitas as jumlah_siswa
            $ruangList = RuangUjian::orderBy('kode')
                ->get()
                ->map(function ($ruang) {
                    $ruang->jumlah_siswa = $ruang->kapasitas;
                    return $ruang;
                });
        }

        $schoolSettings = SchoolSetting::getAllSettings();

        return view('livewire.admin.label-amplop-soal', compact(
            'jadwalList',
            'ruangList',
            'selectedJadwal',
            'schoolSettings',
            'hasPenempatan'
        ));
    }
}

