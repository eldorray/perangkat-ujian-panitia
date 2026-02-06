<?php

namespace App\Livewire\Admin;

use App\Models\Guru;
use App\Models\JadwalUjian;
use App\Models\KegiatanUjian;
use App\Models\SchoolSetting;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Daftar Hadir Panitia & Pengawas')]
class DaftarHadirPanitiaManagement extends Component
{
    public KegiatanUjian $kegiatanUjian;

    // View mode: 'list', 'panitia', 'pengawas'
    public string $viewMode = 'list';

    // Selected guru IDs for panitia
    public array $selectedPanitia = [];

    // Selected guru IDs for pengawas
    public array $selectedPengawas = [];

    // Search
    public string $search = '';

    public function mount(int $id): void
    {
        $this->kegiatanUjian = KegiatanUjian::with('tahunAjaran')->findOrFail($id);

        // Load saved selections from session if available
        $this->selectedPanitia = session("panitia_selection_{$id}", []);
        $this->selectedPengawas = session("pengawas_selection_{$id}", []);
    }

    public function setViewMode(string $mode): void
    {
        $this->viewMode = $mode;
        $this->search = '';
    }

    public function backToList(): void
    {
        $this->viewMode = 'list';
        $this->search = '';
    }

    public function togglePanitia(int $guruId): void
    {
        if (in_array($guruId, $this->selectedPanitia)) {
            $this->selectedPanitia = array_values(array_diff($this->selectedPanitia, [$guruId]));
        } else {
            $this->selectedPanitia[] = $guruId;
        }

        // Save to session
        session(["panitia_selection_{$this->kegiatanUjian->id}" => $this->selectedPanitia]);
    }

    public function togglePengawas(int $guruId): void
    {
        if (in_array($guruId, $this->selectedPengawas)) {
            $this->selectedPengawas = array_values(array_diff($this->selectedPengawas, [$guruId]));
        } else {
            $this->selectedPengawas[] = $guruId;
        }

        // Save to session
        session(["pengawas_selection_{$this->kegiatanUjian->id}" => $this->selectedPengawas]);
    }

    public function selectAllPanitia(): void
    {
        $guruList = Guru::active()->orderBy('full_name')->get();
        $this->selectedPanitia = $guruList->pluck('id')->toArray();
        session(["panitia_selection_{$this->kegiatanUjian->id}" => $this->selectedPanitia]);
    }

    public function clearPanitia(): void
    {
        $this->selectedPanitia = [];
        session(["panitia_selection_{$this->kegiatanUjian->id}" => $this->selectedPanitia]);
    }

    public function selectAllPengawas(): void
    {
        $guruList = Guru::active()->orderBy('full_name')->get();
        $this->selectedPengawas = $guruList->pluck('id')->toArray();
        session(["pengawas_selection_{$this->kegiatanUjian->id}" => $this->selectedPengawas]);
    }

    public function clearPengawas(): void
    {
        $this->selectedPengawas = [];
        session(["pengawas_selection_{$this->kegiatanUjian->id}" => $this->selectedPengawas]);
    }

    public function render(): View
    {
        $guruQuery = Guru::active()->orderBy('full_name');

        if ($this->search) {
            $guruQuery->where(function ($q) {
                $q->where('full_name', 'like', "%{$this->search}%")
                  ->orWhere('nip', 'like', "%{$this->search}%")
                  ->orWhere('nuptk', 'like', "%{$this->search}%");
            });
        }

        $guruList = $guruQuery->get();

        // Get selected guru data for preview
        $selectedPanitiaData = [];
        $selectedPengawasData = [];

        if (!empty($this->selectedPanitia)) {
            $selectedPanitiaData = Guru::whereIn('id', $this->selectedPanitia)
                ->orderBy('full_name')
                ->get();
        }

        if (!empty($this->selectedPengawas)) {
            $selectedPengawasData = Guru::whereIn('id', $this->selectedPengawas)
                ->orderBy('full_name')
                ->get();
        }

        // Get unique dates from jadwal ujian
        $tanggalUjian = JadwalUjian::where('kegiatan_ujian_id', $this->kegiatanUjian->id)
            ->orderBy('tanggal')
            ->get()
            ->pluck('tanggal')
            ->unique()
            ->values();

        $schoolSettings = SchoolSetting::getAllSettings();

        return view('livewire.admin.daftar-hadir-panitia-management', compact(
            'guruList',
            'selectedPanitiaData',
            'selectedPengawasData',
            'tanggalUjian',
            'schoolSettings'
        ));
    }
}
