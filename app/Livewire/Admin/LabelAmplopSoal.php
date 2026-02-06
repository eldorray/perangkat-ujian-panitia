<?php

namespace App\Livewire\Admin;

use App\Models\JadwalUjian;
use App\Models\KegiatanUjian;
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

    // Custom settings
    public int $jumlahSoalPerRuang = 20;
    public int $jumlahCadangan = 2;

    public function mount(int $id): void
    {
        $this->kegiatanUjian = KegiatanUjian::with('tahunAjaran')->findOrFail($id);

        // Load saved settings
        $settings = session("label_amplop_settings_{$id}", []);
        $this->jumlahSoalPerRuang = $settings['jumlahSoalPerRuang'] ?? 20;
        $this->jumlahCadangan = $settings['jumlahCadangan'] ?? 2;
    }

    public function updatedJumlahSoalPerRuang(): void
    {
        $this->saveSettings();
    }

    public function updatedJumlahCadangan(): void
    {
        $this->saveSettings();
    }

    protected function saveSettings(): void
    {
        session(["label_amplop_settings_{$this->kegiatanUjian->id}" => [
            'jumlahSoalPerRuang' => $this->jumlahSoalPerRuang,
            'jumlahCadangan' => $this->jumlahCadangan,
        ]]);
    }

    public function render(): View
    {
        $jadwalList = JadwalUjian::where('kegiatan_ujian_id', $this->kegiatanUjian->id)
            ->orderBy('tanggal')
            ->orderBy('jam_mulai')
            ->get();

        $ruangList = RuangUjian::orderBy('kode')->get();

        $selectedJadwal = $this->selectedJadwalId
            ? JadwalUjian::find($this->selectedJadwalId)
            : null;

        $schoolSettings = SchoolSetting::getAllSettings();

        return view('livewire.admin.label-amplop-soal', compact(
            'jadwalList',
            'ruangList',
            'selectedJadwal',
            'schoolSettings'
        ));
    }
}
