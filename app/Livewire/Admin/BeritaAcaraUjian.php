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
#[Title('Berita Acara Ujian')]
class BeritaAcaraUjian extends Component
{
    public KegiatanUjian $kegiatanUjian;

    // Selected jadwal for berita acara
    public ?int $selectedJadwalId = null;
    public ?int $selectedRuangId = null;

    // Form data
    public int $jumlahHadir = 0;
    public int $jumlahTidakHadir = 0;
    public string $keterangan = '';
    public string $kendala = '';
    public string $namaPengawas1 = '';
    public string $nipPengawas1 = '';
    public string $namaPengawas2 = '';
    public string $nipPengawas2 = '';

    // Show preview
    public bool $showPreview = false;

    public function mount(int $id): void
    {
        $this->kegiatanUjian = KegiatanUjian::with('tahunAjaran')->findOrFail($id);
    }

    public function updatedSelectedJadwalId(): void
    {
        $this->loadSavedData();
    }

    public function updatedSelectedRuangId(): void
    {
        $this->loadSavedData();
    }

    protected function loadSavedData(): void
    {
        if ($this->selectedJadwalId && $this->selectedRuangId) {
            $key = "berita_acara_{$this->kegiatanUjian->id}_{$this->selectedJadwalId}_{$this->selectedRuangId}";
            $data = session($key, []);

            $this->jumlahHadir = $data['jumlahHadir'] ?? 0;
            $this->jumlahTidakHadir = $data['jumlahTidakHadir'] ?? 0;
            $this->keterangan = $data['keterangan'] ?? '';
            $this->kendala = $data['kendala'] ?? '';
            $this->namaPengawas1 = $data['namaPengawas1'] ?? '';
            $this->nipPengawas1 = $data['nipPengawas1'] ?? '';
            $this->namaPengawas2 = $data['namaPengawas2'] ?? '';
            $this->nipPengawas2 = $data['nipPengawas2'] ?? '';
        }
    }

    public function saveData(): void
    {
        if (!$this->selectedJadwalId || !$this->selectedRuangId) {
            $this->dispatch('toast', type: 'error', message: 'Pilih jadwal dan ruang terlebih dahulu!');
            return;
        }

        $key = "berita_acara_{$this->kegiatanUjian->id}_{$this->selectedJadwalId}_{$this->selectedRuangId}";

        session([$key => [
            'jumlahHadir' => $this->jumlahHadir,
            'jumlahTidakHadir' => $this->jumlahTidakHadir,
            'keterangan' => $this->keterangan,
            'kendala' => $this->kendala,
            'namaPengawas1' => $this->namaPengawas1,
            'nipPengawas1' => $this->nipPengawas1,
            'namaPengawas2' => $this->namaPengawas2,
            'nipPengawas2' => $this->nipPengawas2,
        ]]);

        $this->dispatch('toast', type: 'success', message: 'Data berhasil disimpan!');
    }

    public function togglePreview(): void
    {
        if (!$this->selectedJadwalId || !$this->selectedRuangId) {
            $this->dispatch('toast', type: 'error', message: 'Pilih jadwal dan ruang terlebih dahulu!');
            return;
        }

        $this->showPreview = !$this->showPreview;
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

        $selectedRuang = $this->selectedRuangId
            ? RuangUjian::find($this->selectedRuangId)
            : null;

        $schoolSettings = SchoolSetting::getAllSettings();

        return view('livewire.admin.berita-acara-ujian', compact(
            'jadwalList',
            'ruangList',
            'selectedJadwal',
            'selectedRuang',
            'schoolSettings'
        ));
    }
}
