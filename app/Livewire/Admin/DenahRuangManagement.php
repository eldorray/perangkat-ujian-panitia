<?php

namespace App\Livewire\Admin;

use App\Models\KegiatanUjian;
use App\Models\PenempatanRuangUjian;
use App\Models\RuangUjian;
use App\Models\SchoolSetting;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Denah Ruang Ujian')]
class DenahRuangManagement extends Component
{
    public KegiatanUjian $kegiatanUjian;

    // Selected room for viewing
    public ?int $selectedRuangId = null;

    // Layout settings (dynamic, per ruang)
    public int $kolom = 5;          // Number of desk columns
    public int $siswaPerMeja = 1;   // 1 or 2 students per desk
    public string $urutanKursi = 'zigzag'; // zigzag (S-pattern) or lurus (sequential left-to-right)

    // Show cover mode
    public bool $showCover = false;

    public function mount(int $id): void
    {
        $this->kegiatanUjian = KegiatanUjian::with('tahunAjaran')->findOrFail($id);
    }

    public function selectRuang(int $ruangId): void
    {
        $this->selectedRuangId = $ruangId;
        $this->showCover = false;
        $this->loadSettings($ruangId);
    }

    public function clearSelection(): void
    {
        $this->selectedRuangId = null;
        $this->showCover = false;
    }

    public function toggleCover(): void
    {
        $this->showCover = !$this->showCover;
    }

    /**
     * Load saved settings for a specific room
     */
    protected function loadSettings(int $ruangId): void
    {
        $key = "denah_settings_{$this->kegiatanUjian->id}_{$ruangId}";
        $settings = session($key, []);

        $this->kolom = $settings['kolom'] ?? 5;
        $this->siswaPerMeja = $settings['siswaPerMeja'] ?? 1;
        $this->urutanKursi = $settings['urutanKursi'] ?? 'zigzag';
    }

    /**
     * Save settings when any setting changes
     */
    protected function saveSettings(): void
    {
        if (!$this->selectedRuangId) return;

        $key = "denah_settings_{$this->kegiatanUjian->id}_{$this->selectedRuangId}";
        session([$key => [
            'kolom' => $this->kolom,
            'siswaPerMeja' => $this->siswaPerMeja,
            'urutanKursi' => $this->urutanKursi,
        ]]);
    }

    public function updatedKolom(): void
    {
        $this->kolom = max(1, min(10, $this->kolom));
        $this->saveSettings();
    }

    public function updatedSiswaPerMeja(): void
    {
        $this->siswaPerMeja = in_array($this->siswaPerMeja, [1, 2]) ? $this->siswaPerMeja : 1;
        $this->saveSettings();
    }

    public function updatedUrutanKursi(): void
    {
        $this->saveSettings();
    }

    /**
     * Get cover data for a room (nomor peserta range)
     */
    public function getCoverData(array $ruang): array
    {
        $penempatans = $ruang['penempatans'];

        // Get first and last nomor peserta
        $nomorPesertaList = $penempatans->pluck('nomor_peserta')->filter()->sort()->values();

        return [
            'ruang' => $ruang,
            'nomor_awal' => $nomorPesertaList->first() ?? '-',
            'nomor_akhir' => $nomorPesertaList->last() ?? '-',
            'total_siswa' => $penempatans->count(),
        ];
    }

    public function render(): View
    {
        // Get all rooms with their placements for this kegiatan
        $ruangList = RuangUjian::orderBy('kode')->get();

        $ruangWithPenempatan = $ruangList->map(function ($ruang) {
            $penempatans = PenempatanRuangUjian::where('kegiatan_ujian_id', $this->kegiatanUjian->id)
                ->where('ruang_ujian_id', $ruang->id)
                ->with('siswa')
                ->orderBy('nomor_urut')
                ->get();

            return [
                'id' => $ruang->id,
                'kode' => $ruang->kode,
                'nama' => $ruang->nama,
                'kapasitas' => $ruang->kapasitas,
                'terisi' => $penempatans->count(),
                'penempatans' => $penempatans,
            ];
        })->filter(function ($ruang) {
            return $ruang['terisi'] > 0;
        });

        // Get denah data for selected room
        $denahData = null;
        $coverData = null;
        if ($this->selectedRuangId) {
            $ruang = $ruangWithPenempatan->firstWhere('id', $this->selectedRuangId);
            if ($ruang) {
                $denahData = $this->generateDenahData($ruang);
                $coverData = $this->getCoverData($ruang);
            }
        }

        $schoolSettings = SchoolSetting::getAllSettings();

        return view('livewire.admin.denah-ruang-management', compact(
            'ruangWithPenempatan',
            'denahData',
            'coverData',
            'schoolSettings'
        ));
    }

    /**
     * Generate denah data - arrange students into desks dynamically
     */
    protected function generateDenahData(array $ruang): array
    {
        $penempatans = $ruang['penempatans'];
        $desks = [];

        if ($this->siswaPerMeja === 1) {
            // 1 student per desk
            foreach ($penempatans as $p) {
                $desks[] = [
                    'students' => [
                        [
                            'nomor_urut' => $p->nomor_urut,
                            'nama' => $p->siswa->nama_lengkap ?? '-',
                            'nisn' => $p->siswa->nisn ?? '-',
                            'asal_kelas' => $p->asal_kelas,
                            'nomor_peserta' => $p->nomor_peserta,
                        ],
                    ],
                ];
            }
        } else {
            // 2 students per desk
            for ($i = 0; $i < $penempatans->count(); $i += 2) {
                $desk = ['students' => []];

                $left = $penempatans[$i] ?? null;
                $right = $penempatans[$i + 1] ?? null;

                if ($left) {
                    $desk['students'][] = [
                        'nomor_urut' => $left->nomor_urut,
                        'nama' => $left->siswa->nama_lengkap ?? '-',
                        'nisn' => $left->siswa->nisn ?? '-',
                        'asal_kelas' => $left->asal_kelas,
                        'nomor_peserta' => $left->nomor_peserta,
                    ];
                }
                if ($right) {
                    $desk['students'][] = [
                        'nomor_urut' => $right->nomor_urut,
                        'nama' => $right->siswa->nama_lengkap ?? '-',
                        'nisn' => $right->siswa->nisn ?? '-',
                        'asal_kelas' => $right->asal_kelas,
                        'nomor_peserta' => $right->nomor_peserta,
                    ];
                }

                $desks[] = $desk;
            }
        }

        // Arrange desks into rows
        $rows = array_chunk($desks, $this->kolom);

        // Apply zigzag (S-pattern) if selected: even rows get reversed
        if ($this->urutanKursi === 'zigzag') {
            foreach ($rows as $rowIndex => &$row) {
                if ($rowIndex % 2 === 1) {
                    $row = array_reverse($row);
                }
            }
            unset($row);
        }

        return [
            'ruang' => $ruang,
            'rows' => $rows,
            'total_siswa' => $penempatans->count(),
            'total_meja' => count($desks),
            'kolom' => $this->kolom,
            'siswaPerMeja' => $this->siswaPerMeja,
            'urutanKursi' => $this->urutanKursi,
        ];
    }
}
