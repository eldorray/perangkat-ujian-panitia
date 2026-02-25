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

    // Get available pengawas codes for a specific jadwal and ruang (excluding already assigned in same time slot)
    public function getAvailablePengawas(int $jadwalId, int $ruangId): array
    {
        // Find all jadwal IDs in the same time slot (same tanggal + sort_order)
        $jadwal = JadwalUjian::find($jadwalId);
        $sameSlotIds = $jadwal
            ? JadwalUjian::where('kegiatan_ujian_id', $this->kegiatanUjian->id)
                ->where('tanggal', $jadwal->tanggal)
                ->where('sort_order', $jadwal->sort_order)
                ->pluck('id')
                ->toArray()
            : [$jadwalId];

        $assignedCodes = [];
        foreach ($sameSlotIds as $slotJadwalId) {
            foreach ($this->assignments[$slotJadwalId] ?? [] as $existingRuangId => $existingCode) {
                // Skip current ruang in current jadwal
                if ($slotJadwalId == $jadwalId && $existingRuangId == $ruangId) continue;
                if ($existingCode !== '' && !is_array($existingCode)) {
                    $assignedCodes[] = (string) $existingCode;
                }
            }
        }

        $available = [];
        foreach (range(1, count($this->selectedPengawas)) as $code) {
            if (!in_array((string) $code, $assignedCodes)) {
                $available[] = (string) $code;
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

    public function cycleAssignment(int $jadwalId, int $ruangId): void
    {
        $currentValue = $this->getAssignmentValue($jadwalId, $ruangId);
        $availableCodes = $this->getAvailablePengawas($jadwalId, $ruangId);

        // Add current value back to available list (so we can cycle past it)
        if ($currentValue !== '' && !in_array($currentValue, $availableCodes)) {
            $availableCodes[] = $currentValue;
            sort($availableCodes, SORT_NUMERIC);
        }

        if (empty($availableCodes)) {
            return;
        }

        if ($currentValue === '') {
            // Nothing assigned yet -> assign first available
            $newValue = $availableCodes[0];
        } else {
            // Find next in cycle
            $currentIndex = array_search($currentValue, $availableCodes);
            if ($currentIndex === false || $currentIndex >= count($availableCodes) - 1) {
                // Last in list or not found -> clear
                $newValue = '';
            } else {
                $newValue = $availableCodes[$currentIndex + 1];
            }
        }

        $this->updateAssignment($jadwalId, $ruangId, $newValue);
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

        // Statistik beban mengawas
        $pengawasStats = $this->getPengawasStats();

        // === Print preview data ===
        // Generate unique initials and colors for each pengawas
        $colors = ['#FFD700','#87CEEB','#FFB6C1','#98FB98','#DDA0DD','#FFA07A','#B0E0E6','#F0E68C','#D8BFD8','#FFDAB9','#E6E6FA','#F5DEB3','#C1FFC1','#FFE4E1','#B0C4DE','#FAEBD7','#F0FFF0','#FFF0F5','#F5F5DC','#E0FFFF'];
        $usedInitials = [];
        $codeToInitial = [];
        $codeToColor = [];

        foreach ($pengawasData as $i => &$pd) {
            $words = preg_split('/\s+/', preg_replace('/^(Drs\.|Dr\.|Prof\.|Hj\.|H\.|S\.Pd\.?I?|M\.Pd\.?|S\.Ag|M\.Ag|S\.Sos|M\.Si|Lc\.?|M\.A\.?)\s*/i', '', $pd['guru']->full_name));
            $words = array_values(array_filter($words, fn($w) => mb_strlen($w) > 1));
            if (empty($words)) $words = [$pd['guru']->full_name];

            $initial = $this->generateInitial($words, $usedInitials);
            $usedInitials[] = $initial;
            $codeToInitial[(string)$pd['code']] = $initial;
            $codeToColor[(string)$pd['code']] = $colors[$i % count($colors)];
            $pd['initial'] = $initial;
            $pd['color'] = $colors[$i % count($colors)];
        }
        unset($pd);

        // Build kelas-to-ruang mapping
        $kelasRuangMap = [];
        $placements = PenempatanRuangUjian::where('kegiatan_ujian_id', $this->kegiatanUjian->id)
            ->selectRaw('kelas_nama, ruang_ujian_id')
            ->distinct()
            ->get();
        foreach ($placements as $p) {
            $kelasRuangMap[$p->kelas_nama] = $p->ruang_ujian_id;
        }

        // Build kelas groups for print (like the image: Kelas 1&2, Kelas 3, Kelas 4,5,6)
        $allKelas = Kelas::orderBy('tingkat')->orderBy('nama')->get();
        $romanToArabic = ['I'=>'1','II'=>'2','III'=>'3','IV'=>'4','V'=>'5','VI'=>'6'];

        $kelompokValues = $allJadwal->pluck('kelompok_kelas')->filter()->unique()->sort()->values();
        if ($kelompokValues->isEmpty()) {
            // No kelompok_kelas set - put all in one group
            $printGroups = [['label' => 'Semua Kelas', 'kelompok_values' => [null, ''],
                'kelasList' => $allKelas->map(fn($k) => [
                    'nama' => $k->nama, 'short' => preg_replace('/^Kelas\s+/', '', $k->nama),
                    'ruang_id' => $kelasRuangMap[$k->nama] ?? null,
                ])->toArray(),
            ]];
        } else {
            // Build groups per kelompok, then merge those with same jadwal pattern
            $rawGroups = [];
            foreach ($kelompokValues as $kv) {
                // Strip "Kelas " prefix from kelompok_kelas to match Kelas.tingkat
                $cleanTingkat = preg_replace('/^Kelas\s+/i', '', $kv);
                $kelasList = $allKelas->filter(fn($k) => $k->tingkat === $cleanTingkat || $k->tingkat === $kv)->values();
                $rawGroups[] = [
                    'kelompok_values' => [$kv],
                    'kelasList' => $kelasList->map(fn($k) => [
                        'nama' => $k->nama, 'short' => preg_replace('/^Kelas\s+/', '', $k->nama),
                        'ruang_id' => $kelasRuangMap[$k->nama] ?? null,
                    ])->toArray(),
                ];
            }
            // Merge groups with same jadwal pattern
            $patterns = [];
            foreach ($rawGroups as $i => $group) {
                $pattern = [];
                foreach ($allJadwal as $j) {
                    if (in_array($j->kelompok_kelas, $group['kelompok_values'])) {
                        $pattern[$j->tanggal->format('Y-m-d').'|'.$j->sort_order] = $j->mata_pelajaran;
                    }
                }
                ksort($pattern);
                $patterns[$i] = serialize($pattern);
            }
            $printGroups = [];
            $processed = [];
            foreach ($patterns as $i => $pattern) {
                if (in_array($i, $processed)) continue;
                $mg = $rawGroups[$i];
                for ($jj = $i + 1; $jj < count($rawGroups); $jj++) {
                    if (in_array($jj, $processed)) continue;
                    if ($patterns[$jj] === $pattern) {
                        $mg['kelasList'] = array_merge($mg['kelasList'], $rawGroups[$jj]['kelasList']);
                        $mg['kelompok_values'] = array_merge($mg['kelompok_values'], $rawGroups[$jj]['kelompok_values']);
                        $processed[] = $jj;
                    }
                }
                $processed[] = $i;
                // Convert kelompok_values to arabic for label (strip "Kelas " prefix first)
                $arabicVals = collect($mg['kelompok_values'])->sort()->map(function($t) use ($romanToArabic) {
                    $clean = preg_replace('/^Kelas\s+/i', '', $t);
                    return $romanToArabic[$clean] ?? $clean;
                })->values();
                $mg['label'] = 'Kelas ' . $arabicVals->join(', ', ' & ');
                $printGroups[] = $mg;
            }
        }

        // Build time slots for print (grouped by date + sort_order)
        $printTimeSlots = [];
        $grouped = $allJadwal->groupBy(fn($j) => $j->tanggal->format('Y-m-d') . '|' . $j->sort_order);
        foreach ($grouped->sortKeys() as $key => $jadwals) {
            [$dateStr, $sortOrder] = explode('|', $key);
            $first = $jadwals->first();
            $printTimeSlots[] = [
                'tanggal' => $first->tanggal,
                'sort_order' => (int)$sortOrder,
                'waktu' => $first->waktu,
                'jadwals' => $jadwals,
            ];
        }

        return view('livewire.admin.jadwal-mengawas-management', compact(
            'guruList',
            'pengawasData',
            'jadwalList',
            'ruangList',
            'schoolSettings',
            'pengawasStats',
            'kelompokOptions',
            'printGroups',
            'printTimeSlots',
            'codeToInitial',
            'codeToColor'
        ));
    }

    private function generateInitial(array $words, array $used): string
    {
        // Primary: first letter of each word (e.g., "Abdul Hamid" → "AH")
        $primary = strtoupper(implode('', array_map(fn($w) => mb_substr($w, 0, 1), $words)));
        if (!in_array($primary, $used)) return $primary;

        // Fallback 1: first letter of first word + first 2 chars of last word (e.g., "AHA" for "Abdul Hamid Ahmad")
        if (count($words) >= 2) {
            $lastWord = end($words);
            for ($len = 2; $len <= mb_strlen($lastWord); $len++) {
                $c = strtoupper(mb_substr($words[0], 0, 1) . mb_substr($lastWord, 0, $len));
                if (!in_array($c, $used)) return $c;
            }
        }

        // Fallback 2: first 2 chars of first word + first letter of remaining words
        if (mb_strlen($words[0]) >= 2) {
            $c = strtoupper(mb_substr($words[0], 0, 2) . implode('', array_map(fn($w) => mb_substr($w, 0, 1), array_slice($words, 1))));
            if (!in_array($c, $used)) return $c;
        }

        // Fallback 3: progressively longer substrings of first word
        for ($len = 2; $len <= mb_strlen($words[0] ?? ''); $len++) {
            $c = strtoupper(mb_substr($words[0], 0, $len));
            if (!in_array($c, $used)) return $c;
        }

        // Last resort: append number suffix to primary (e.g., "AH1", "AH2")
        for ($num = 1; $num <= 99; $num++) {
            $c = $primary . $num;
            if (!in_array($c, $used)) return $c;
        }

        return (string)(count($used) + 1);
    }
}
