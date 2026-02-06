<?php

namespace App\Livewire\Admin;

use App\Helpers\NumberHelper;
use App\Models\Guru;
use App\Models\KegiatanUjian;
use App\Models\SchoolSetting;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Livewire\Component;

class HonorInsentifKalkulator extends Component
{
    private const SESSION_KEY_PREFIX = 'honor_insentif_';

    public KegiatanUjian $kegiatanUjian;

    #[Validate('required|integer|min:0')]
    public int $honorPerHadirPanitia = 0;

    #[Validate('required|integer|min:0')]
    public int $honorPerHadirPengawas = 0;

    public array $daftarPanitia = [];
    public array $daftarPengawas = [];
    public array $itemTambahan = [];
    public bool $showPreview = false;
    public array $availableGurus = [];

    protected array $rules = [
        'daftarPanitia.*.guru_id' => 'nullable|integer',
        'daftarPanitia.*.nama' => 'nullable|string|max:255',
        'daftarPanitia.*.jabatan' => 'nullable|string|max:100',
        'daftarPanitia.*.jumlah_hadir' => 'required|integer|min:1',
        'daftarPengawas.*.guru_id' => 'nullable|integer',
        'daftarPengawas.*.nama' => 'nullable|string|max:255',
        'daftarPengawas.*.jumlah_hadir' => 'required|integer|min:1',
        'itemTambahan.*.nama' => 'nullable|string|max:255',
        'itemTambahan.*.jumlah' => 'required|integer|min:1',
        'itemTambahan.*.harga' => 'required|integer|min:0',
    ];

    public function mount(int $id): void
    {
        $this->kegiatanUjian = KegiatanUjian::with('tahunAjaran')->findOrFail($id);
        $this->loadAvailableGurus();
        $this->loadFromSession();
    }

    private function loadAvailableGurus(): void
    {
        $this->availableGurus = Guru::where('is_active', true)
            ->orderBy('full_name')
            ->get(['id', 'front_title', 'full_name', 'back_title', 'nip'])
            ->map(fn(Guru $guru) => [
                'id' => $guru->id,
                'nama' => $guru->full_name_with_titles,
                'nip' => $guru->nip,
            ])
            ->toArray();
    }

    private function getSessionKey(): string
    {
        return self::SESSION_KEY_PREFIX . $this->kegiatanUjian->id;
    }

    public function loadFromSession(): void
    {
        $data = session($this->getSessionKey(), []);

        $this->honorPerHadirPanitia = $data['honorPerHadirPanitia'] ?? 0;
        $this->honorPerHadirPengawas = $data['honorPerHadirPengawas'] ?? 0;
        $this->daftarPanitia = $data['daftarPanitia'] ?? [];
        $this->daftarPengawas = $data['daftarPengawas'] ?? [];
        $this->itemTambahan = $data['itemTambahan'] ?? [];
    }

    public function saveToSession(): void
    {
        session([
            $this->getSessionKey() => [
                'honorPerHadirPanitia' => $this->honorPerHadirPanitia,
                'honorPerHadirPengawas' => $this->honorPerHadirPengawas,
                'daftarPanitia' => $this->daftarPanitia,
                'daftarPengawas' => $this->daftarPengawas,
                'itemTambahan' => $this->itemTambahan,
            ],
        ]);

        $this->dispatch('notify', type: 'success', message: 'Data berhasil disimpan!');
    }

    // Panitia management
    public function addPanitia(): void
    {
        $this->daftarPanitia[] = [
            'guru_id' => '',
            'nama' => '',
            'jabatan' => '',
            'jumlah_hadir' => 1,
        ];
    }

    public function removePanitia(int $index): void
    {
        unset($this->daftarPanitia[$index]);
        $this->daftarPanitia = array_values($this->daftarPanitia);
    }

    public function updatePanitiaNama(int $index, mixed $guruId): void
    {
        if (! $guruId) {
            return;
        }

        $guru = collect($this->availableGurus)->firstWhere('id', (int) $guruId);

        if ($guru) {
            $this->daftarPanitia[$index]['nama'] = $guru['nama'];
        }
    }

    // Pengawas management
    public function addPengawas(): void
    {
        $this->daftarPengawas[] = [
            'guru_id' => '',
            'nama' => '',
            'jumlah_hadir' => 1,
        ];
    }

    public function removePengawas(int $index): void
    {
        unset($this->daftarPengawas[$index]);
        $this->daftarPengawas = array_values($this->daftarPengawas);
    }

    public function updatePengawasNama(int $index, mixed $guruId): void
    {
        if (! $guruId) {
            return;
        }

        $guru = collect($this->availableGurus)->firstWhere('id', (int) $guruId);

        if ($guru) {
            $this->daftarPengawas[$index]['nama'] = $guru['nama'];
        }
    }

    // Item Tambahan management
    public function addItemTambahan(): void
    {
        $this->itemTambahan[] = [
            'nama' => '',
            'jumlah' => 1,
            'satuan' => 'unit',
            'harga' => 0,
        ];
    }

    public function removeItemTambahan(int $index): void
    {
        unset($this->itemTambahan[$index]);
        $this->itemTambahan = array_values($this->itemTambahan);
    }

    // Computed properties
    #[Computed]
    public function totalKehadiranPanitia(): int
    {
        return collect($this->daftarPanitia)->sum('jumlah_hadir');
    }

    #[Computed]
    public function totalHonorPanitia(): int
    {
        return collect($this->daftarPanitia)
            ->sum(fn(array $p) => ($p['jumlah_hadir'] ?? 0) * $this->honorPerHadirPanitia);
    }

    #[Computed]
    public function totalKehadiranPengawas(): int
    {
        return collect($this->daftarPengawas)->sum('jumlah_hadir');
    }

    #[Computed]
    public function totalHonorPengawas(): int
    {
        return collect($this->daftarPengawas)
            ->sum(fn(array $p) => ($p['jumlah_hadir'] ?? 0) * $this->honorPerHadirPengawas);
    }

    #[Computed]
    public function totalItemTambahan(): int
    {
        return collect($this->itemTambahan)
            ->sum(fn(array $item) => ($item['jumlah'] ?? 0) * ($item['harga'] ?? 0));
    }

    #[Computed]
    public function grandTotal(): int
    {
        return $this->totalHonorPanitia + $this->totalHonorPengawas + $this->totalItemTambahan;
    }

    #[Computed]
    public function guruOptions(): array
    {
        return collect($this->availableGurus)
            ->map(fn(array $g) => ['value' => $g['id'], 'label' => $g['nama']])
            ->toArray();
    }

    public function togglePreview(): void
    {
        $this->showPreview = ! $this->showPreview;
    }

    public function terbilang(int|float $angka): string
    {
        return NumberHelper::terbilang($angka);
    }

    public function render()
    {
        return view('livewire.admin.honor-insentif-kalkulator', [
            'schoolSettings' => SchoolSetting::getAllSettings(),
        ])->layout('layouts.admin', ['title' => 'Kalkulator Honor/Insentif']);
    }
}
