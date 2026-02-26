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

    // Selected kelompok kelas tab
    public string $selectedKelompok = '';

    // Jadwal data: jadwal_id => ['jumlah_soal' => x, 'jumlah_cadangan' => y]
    public array $jadwalData = [];

    public function mount(int $id): void
    {
        $this->kegiatanUjian = KegiatanUjian::with('tahunAjaran')->findOrFail($id);

        // Auto-select first kelompok
        $kelompokList = $this->getKelompokList();
        if ($kelompokList->isNotEmpty()) {
            $this->selectedKelompok = $kelompokList->first();
        }

        $this->loadJadwalData();
    }

    public function updatedSelectedKelompok(): void
    {
        $this->loadJadwalData();
    }

    protected function loadJadwalData(): void
    {
        $jadwals = $this->getFilteredJadwals();
        $this->jadwalData = [];
        foreach ($jadwals as $jadwal) {
            $this->jadwalData[$jadwal->id] = [
                'jumlah_soal' => $jadwal->jumlah_soal ?? 0,
                'jumlah_cadangan' => $jadwal->jumlah_cadangan ?? 0,
            ];
        }
    }

    protected function getKelompokList()
    {
        return JadwalUjian::where('kegiatan_ujian_id', $this->kegiatanUjian->id)
            ->whereNotNull('kelompok_kelas')
            ->where('kelompok_kelas', '!=', '')
            ->distinct()
            ->orderBy('kelompok_kelas')
            ->pluck('kelompok_kelas');
    }

    protected function getFilteredJadwals()
    {
        $query = JadwalUjian::where('kegiatan_ujian_id', $this->kegiatanUjian->id);

        if ($this->selectedKelompok) {
            $query->where('kelompok_kelas', $this->selectedKelompok);
        }

        return $query->orderBy('tanggal')
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
        $kelompokList = $this->getKelompokList();
        $jadwals = $this->getFilteredJadwals();

        // Get rooms with student count for printing
        $siswaPerRuang = PenempatanRuangUjian::where('kegiatan_ujian_id', $this->kegiatanUjian->id)
            ->selectRaw('ruang_ujian_id, COUNT(*) as jumlah_siswa')
            ->groupBy('ruang_ujian_id')
            ->pluck('jumlah_siswa', 'ruang_ujian_id');

        if ($siswaPerRuang->isNotEmpty()) {
            $ruangList = RuangUjian::whereIn('id', $siswaPerRuang->keys())
                ->orderBy('kode')
                ->get();
        } else {
            $ruangList = RuangUjian::orderBy('kode')->get();
        }

        $schoolSettings = SchoolSetting::getAllSettings();

        return view('livewire.admin.label-amplop-soal', compact(
            'kelompokList',
            'jadwals',
            'ruangList',
            'schoolSettings'
        ));
    }
}
