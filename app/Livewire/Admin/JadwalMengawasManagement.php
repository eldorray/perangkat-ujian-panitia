<?php

namespace App\Livewire\Admin;

use App\Models\Guru;
use App\Models\JadwalUjian;
use App\Models\KegiatanUjian;
use App\Models\RuangUjian;
use App\Models\SchoolSetting;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Jadwal Mengawas')]
class JadwalMengawasManagement extends Component
{
    public KegiatanUjian $kegiatanUjian;

    // Selected pengawas guru IDs
    public array $selectedPengawas = [];

    // Assignment data: [jadwal_id => [ruang_id => pengawas_code]]
    public array $assignments = [];

    // Search
    public string $search = '';

    // Show print preview
    public bool $showPreview = false;

    public function mount(int $id): void
    {
        $this->kegiatanUjian = KegiatanUjian::with('tahunAjaran')->findOrFail($id);

        // Load saved selections from session
        $this->selectedPengawas = session("jadwal_mengawas_pengawas_{$id}", []);
        $this->assignments = session("jadwal_mengawas_assignments_{$id}", []);
    }

    public function togglePengawas(int $guruId): void
    {
        if (in_array($guruId, $this->selectedPengawas)) {
            $this->selectedPengawas = array_values(array_diff($this->selectedPengawas, [$guruId]));
        } else {
            $this->selectedPengawas[] = $guruId;
        }

        session(["jadwal_mengawas_pengawas_{$this->kegiatanUjian->id}" => $this->selectedPengawas]);
    }

    public function selectAllPengawas(): void
    {
        $guruList = Guru::active()->orderBy('full_name')->get();
        $this->selectedPengawas = $guruList->pluck('id')->toArray();
        session(["jadwal_mengawas_pengawas_{$this->kegiatanUjian->id}" => $this->selectedPengawas]);
    }

    public function clearPengawas(): void
    {
        $this->selectedPengawas = [];
        session(["jadwal_mengawas_pengawas_{$this->kegiatanUjian->id}" => $this->selectedPengawas]);
    }

    public function updateAssignment(int $jadwalId, int $ruangId, string $value): void
    {
        $code = trim($value);

        // Validate: check if this pengawas is already assigned to another room in the same jadwal
        if ($code !== '' && $code !== '-') {
            foreach ($this->assignments[$jadwalId] ?? [] as $existingRuangId => $existingCode) {
                if ($existingRuangId != $ruangId && $existingCode === $code) {
                    $this->dispatch('toast', type: 'error', message: "Pengawas kode {$code} sudah ditugaskan di ruangan lain pada jadwal yang sama!");
                    return;
                }
            }
        }

        if (!isset($this->assignments[$jadwalId])) {
            $this->assignments[$jadwalId] = [];
        }

        $this->assignments[$jadwalId][$ruangId] = $code === '' || $code === '-' ? '' : $code;

        session(["jadwal_mengawas_assignments_{$this->kegiatanUjian->id}" => $this->assignments]);
    }

    public function getAssignmentValue(int $jadwalId, int $ruangId): string
    {
        $value = $this->assignments[$jadwalId][$ruangId] ?? '';

        // Handle legacy data that might be stored as array
        if (is_array($value)) {
            return implode(', ', $value);
        }

        return (string) $value;
    }

    // Get available pengawas codes for a specific jadwal and ruang (excluding already assigned)
    public function getAvailablePengawas(int $jadwalId, int $ruangId): array
    {
        $assignedCodes = [];

        foreach ($this->assignments[$jadwalId] ?? [] as $existingRuangId => $existingCode) {
            if ($existingRuangId != $ruangId && $existingCode !== '' && !is_array($existingCode)) {
                $assignedCodes[] = (string)$existingCode;
            }
        }

        $available = [];
        foreach (range(1, count($this->selectedPengawas)) as $code) {
            if (!in_array((string)$code, $assignedCodes)) {
                $available[] = (string)$code;
            }
        }

        return $available;
    }

    // Check if a code is already assigned in the same jadwal (for different ruang)
    public function isCodeAssigned(int $jadwalId, int $ruangId, string $code): bool
    {
        if ($code === '' || $code === '-') {
            return false;
        }

        foreach ($this->assignments[$jadwalId] ?? [] as $existingRuangId => $existingCode) {
            // Skip current ruang
            if ($existingRuangId == $ruangId) {
                continue;
            }

            // Handle legacy array data
            $checkCode = is_array($existingCode) ? implode(',', $existingCode) : (string)$existingCode;

            if ($checkCode === $code) {
                return true;
            }
        }

        return false;
    }

    // Get ruang name where a code is already assigned
    public function getAssignedRuangName(int $jadwalId, int $ruangId, string $code): string
    {
        if ($code === '' || $code === '-') {
            return '';
        }

        $ruangList = RuangUjian::orderBy('kode')->get()->keyBy('id');

        foreach ($this->assignments[$jadwalId] ?? [] as $existingRuangId => $existingCode) {
            if ($existingRuangId == $ruangId) {
                continue;
            }

            $checkCode = is_array($existingCode) ? implode(',', $existingCode) : (string)$existingCode;

            if ($checkCode === $code && isset($ruangList[$existingRuangId])) {
                return $ruangList[$existingRuangId]->kode;
            }
        }

        return '';
    }

    public function togglePreview(): void
    {
        $this->showPreview = !$this->showPreview;
    }

    public function autoAssign(): void
    {
        if (empty($this->selectedPengawas)) {
            $this->dispatch('toast', type: 'error', message: 'Pilih pengawas terlebih dahulu!');
            return;
        }

        $jadwalList = JadwalUjian::where('kegiatan_ujian_id', $this->kegiatanUjian->id)
            ->orderBy('tanggal')
            ->orderBy('jam_mulai')
            ->get();

        $ruangList = RuangUjian::orderBy('kode')->get();

        $pengawasCount = count($this->selectedPengawas);

        $this->assignments = [];

        foreach ($jadwalList as $jadwal) {
            $this->assignments[$jadwal->id] = [];

            // Shuffle pengawas codes for random assignment per jadwal
            $codes = range(1, $pengawasCount);
            shuffle($codes);

            $codeIndex = 0;

            foreach ($ruangList as $ruang) {
                // Assign 1 pengawas per room
                if ($codeIndex < $pengawasCount) {
                    $this->assignments[$jadwal->id][$ruang->id] = (string)$codes[$codeIndex];
                    $codeIndex++;
                } else {
                    // If more rooms than pengawas, leave empty or cycle
                    $this->assignments[$jadwal->id][$ruang->id] = '';
                }
            }
        }

        session(["jadwal_mengawas_assignments_{$this->kegiatanUjian->id}" => $this->assignments]);

        $this->dispatch('toast', type: 'success', message: 'Pengawas berhasil diacak otomatis!');
    }

    public function clearAssignments(): void
    {
        $this->assignments = [];
        session(["jadwal_mengawas_assignments_{$this->kegiatanUjian->id}" => $this->assignments]);
        $this->dispatch('toast', type: 'success', message: 'Semua penugasan berhasil dihapus!');
    }

    public function render(): View
    {
        $guruQuery = Guru::active()->orderBy('full_name');

        if ($this->search) {
            $guruQuery->where(function ($q) {
                $q->where('full_name', 'like', "%{$this->search}%")
                    ->orWhere('nip', 'like', "%{$this->search}%");
            });
        }

        $guruList = $guruQuery->get();

        // Get selected pengawas with their codes (1-based index)
        $pengawasData = [];
        if (!empty($this->selectedPengawas)) {
            $selectedGuruList = Guru::whereIn('id', $this->selectedPengawas)
                ->orderBy('full_name')
                ->get();

            foreach ($selectedGuruList as $index => $guru) {
                $pengawasData[] = [
                    'code' => $index + 1,
                    'guru' => $guru,
                ];
            }
        }

        // Get jadwal ujian
        $jadwalList = JadwalUjian::where('kegiatan_ujian_id', $this->kegiatanUjian->id)
            ->orderBy('tanggal')
            ->orderBy('jam_mulai')
            ->get();

        // Get ruang ujian
        $ruangList = RuangUjian::orderBy('kode')->get();

        $schoolSettings = SchoolSetting::getAllSettings();

        return view('livewire.admin.jadwal-mengawas-management', compact(
            'guruList',
            'pengawasData',
            'jadwalList',
            'ruangList',
            'schoolSettings'
        ));
    }
}
