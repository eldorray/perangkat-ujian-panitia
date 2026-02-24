<?php

namespace App\Livewire\Admin;

use App\Models\Guru;
use App\Models\JadwalUjian;
use App\Models\KegiatanUjian;
use App\Models\Kelas;
use App\Models\PenempatanRuangUjian;
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

    // Filter by kelompok kelas
    public string $filterKelompok = '';

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
        $ruangIds = $ruangList->pluck('id')->toArray();
        $ruangCount = count($ruangIds);

        $this->assignments = [];

        // Acak kode SEKALI saja — kode guru tetap konsisten di semua hari
        $codes = range(1, $pengawasCount);
        shuffle($codes);

        foreach ($jadwalList as $jadwalIndex => $jadwal) {
            $this->assignments[$jadwal->id] = [];

            // Rotasi offset berdasarkan index jadwal
            // Sehingga guru tidak selalu mengawas di ruang yang sama
            $offset = $jadwalIndex % $pengawasCount;

            foreach ($ruangIds as $ruangIndex => $ruangId) {
                if ($ruangIndex < $pengawasCount) {
                    $codeIdx = ($ruangIndex + $offset) % $pengawasCount;
                    $this->assignments[$jadwal->id][$ruangId] = (string)$codes[$codeIdx];
                } else {
                    $this->assignments[$jadwal->id][$ruangId] = '';
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

    /**
     * Hitung statistik beban mengawas per kode pengawas
     */
    public function getPengawasStats(): array
    {
        $stats = [];
        if (empty($this->selectedPengawas)) {
            return $stats;
        }

        $pengawasCount = count($this->selectedPengawas);
        for ($code = 1; $code <= $pengawasCount; $code++) {
            $count = 0;
            foreach ($this->assignments as $jadwalAssignments) {
                if (in_array((string)$code, array_values(is_array($jadwalAssignments) ? $jadwalAssignments : []), true)) {
                    $count++;
                }
            }
            $stats[(string)$code] = $count;
        }

        return $stats;
    }

    private function generateUniqueInitial(array $words, array $usedInitials): string
    {
        $candidates = [];
        if (isset($words[0])) $candidates[] = strtoupper(mb_substr($words[0], 0, 1));
        if (count($words) >= 2) $candidates[] = strtoupper(mb_substr($words[0], 0, 1) . mb_substr($words[1], 0, 1));
        if (isset($words[0]) && mb_strlen($words[0]) >= 2) $candidates[] = strtoupper(mb_substr($words[0], 0, 2));
        if (count($words) >= 3) $candidates[] = strtoupper(mb_substr($words[0], 0, 1) . mb_substr($words[1], 0, 1) . mb_substr($words[2], 0, 1));

        foreach ($candidates as $c) {
            if (!in_array($c, $usedInitials)) return $c;
        }
        for ($len = 3; $len <= mb_strlen($words[0] ?? ''); $len++) {
            $c = strtoupper(mb_substr($words[0], 0, $len));
            if (!in_array($c, $usedInitials)) return $c;
        }
        return (string)(count($usedInitials) + 1);
    }

    private function mergePrintGroups(array $groups, $allJadwal): array
    {
        if (count($groups) <= 1) return $groups;
        $romanToArabic = ['I'=>'1','II'=>'2','III'=>'3','IV'=>'4','V'=>'5','VI'=>'6'];

        // Build jadwal pattern for each group
        $patterns = [];
        foreach ($groups as $i => $group) {
            $pattern = [];
            foreach ($allJadwal as $j) {
                if (in_array($j->kelompok_kelas, $group['kelompok_values'])) {
                    $key = $j->tanggal->format('Y-m-d') . '|' . $j->sort_order;
                    $pattern[$key] = $j->mata_pelajaran;
                }
            }
            ksort($pattern);
            $patterns[$i] = serialize($pattern);
        }

        // Merge groups with same pattern
        $merged = [];
        $processed = [];
        foreach ($patterns as $i => $pattern) {
            if (in_array($i, $processed)) continue;
            $mg = $groups[$i];
            for ($j = $i + 1; $j < count($groups); $j++) {
                if (in_array($j, $processed)) continue;
                if ($patterns[$j] === $pattern) {
                    $mg['kelasList'] = array_merge($mg['kelasList'], $groups[$j]['kelasList']);
                    $mg['kelompok_values'] = array_merge($mg['kelompok_values'], $groups[$j]['kelompok_values']);
                    $processed[] = $j;
                }
            }
            $processed[] = $i;
            // Build label
            $arabicVals = collect($mg['kelompok_values'])->sort()->map(fn($t) => $romanToArabic[$t] ?? $t)->values();
            $mg['label'] = 'Kelas ' . $arabicVals->join(', ', ' & ');
            $merged[] = $mg;
        }
        return $merged;
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
        $jadwalQuery = JadwalUjian::where('kegiatan_ujian_id', $this->kegiatanUjian->id)
            ->orderBy('kelompok_kelas')
            ->orderBy('tanggal')
            ->orderBy('sort_order')
            ->orderBy('jam_mulai');

        // Get all jadwal for grouping options
        $allJadwal = $jadwalQuery->get();

        // Get unique kelompok_kelas options
        $kelompokOptions = $allJadwal
            ->pluck('kelompok_kelas')
            ->map(fn($k) => $k ?: 'Semua Kelas')
            ->unique()
            ->values()
            ->toArray();

        // Filter jadwal by kelompok if selected
        if ($this->filterKelompok !== '') {
            $jadwalList = $allJadwal->filter(function ($j) {
                if ($this->filterKelompok === 'Semua Kelas') {
                    return $j->kelompok_kelas === null || $j->kelompok_kelas === '';
                }
                return $j->kelompok_kelas === $this->filterKelompok;
            })->values();
        } else {
            $jadwalList = $allJadwal;
        }

        // Get ruang ujian
        $ruangList = RuangUjian::orderBy('kode')->get();

        $schoolSettings = SchoolSetting::getAllSettings();
        $pengawasStats = $this->getPengawasStats();

        // === Print preview data ===
        $kelasRuangMap = [];
        $placements = PenempatanRuangUjian::where('kegiatan_ujian_id', $this->kegiatanUjian->id)
            ->selectRaw('kelas_nama, ruang_ujian_id')
            ->distinct()
            ->get();
        foreach ($placements as $p) {
            $kelasRuangMap[$p->kelas_nama] = $p->ruang_ujian_id;
        }

        $allKelas = Kelas::orderBy('tingkat')->orderBy('nama')->get();
        $kelompokValues = $allJadwal->pluck('kelompok_kelas')->filter()->unique()->sort()->values();

        if ($kelompokValues->isNotEmpty()) {
            $rawGroups = [];
            foreach ($kelompokValues as $kv) {
                $kelasList = $allKelas->filter(fn($k) => $k->tingkat === $kv)->values();
                $rawGroups[] = [
                    'kelompok_values' => [$kv],
                    'kelasList' => $kelasList->map(fn($k) => [
                        'nama' => $k->nama,
                        'short' => preg_replace('/^Kelas\s+/', '', $k->nama),
                        'ruang_id' => $kelasRuangMap[$k->nama] ?? null,
                    ])->toArray(),
                ];
            }
            $printGroups = $this->mergePrintGroups($rawGroups, $allJadwal);
        } else {
            $printGroups = [[
                'label' => 'Semua Kelas',
                'kelasList' => $allKelas->map(fn($k) => [
                    'nama' => $k->nama,
                    'short' => preg_replace('/^Kelas\s+/', '', $k->nama),
                    'ruang_id' => $kelasRuangMap[$k->nama] ?? null,
                ])->toArray(),
                'kelompok_values' => [null],
            ]];
        }

        // Build time slots for print
        $printTimeSlots = [];
        $grouped = $allJadwal->groupBy(fn($j) => $j->tanggal->format('Y-m-d') . '|' . $j->sort_order);
        foreach ($grouped->sortKeys() as $key => $jadwals) {
            $first = $jadwals->first();
            $printTimeSlots[] = [
                'tanggal' => $first->tanggal,
                'sort_order' => $first->sort_order,
                'waktu' => substr($first->jam_mulai, 0, 5) . ' - ' . substr($first->jam_selesai, 0, 5),
                'jadwals' => $jadwals,
            ];
        }

        // Generate initials & colors for pengawas
        $colorPalette = [
            '#FECACA','#FED7AA','#FDE68A','#D9F99D','#BBF7D0',
            '#A7F3D0','#99F6E4','#A5F3FC','#BAE6FD','#BFDBFE',
            '#C7D2FE','#DDD6FE','#E9D5FF','#F5D0FE','#FBCFE8',
            '#FCA5A1','#FDBA74','#FCD34D','#BEF264','#86EFAC',
        ];
        $usedInitials = [];
        $codeToInitial = [];
        $codeToColor = [];
        foreach ($pengawasData as $idx => &$pd) {
            $words = preg_split('/\s+/', trim($pd['guru']->full_name));
            $initial = $this->generateUniqueInitial($words, $usedInitials);
            $usedInitials[] = $initial;
            $pd['initial'] = $initial;
            $pd['color'] = $colorPalette[$idx % count($colorPalette)];
            $codeToInitial[(string)$pd['code']] = $initial;
            $codeToColor[(string)$pd['code']] = $pd['color'];
        }
        unset($pd);

        return view('livewire.admin.jadwal-mengawas-management', compact(
            'guruList', 'pengawasData', 'jadwalList', 'ruangList',
            'schoolSettings', 'pengawasStats', 'kelompokOptions',
            'printGroups', 'printTimeSlots', 'codeToInitial', 'codeToColor'
        ));
    }
}
