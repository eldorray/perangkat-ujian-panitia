<?php

namespace App\Livewire\Admin;

use App\Models\Guru;
use Illuminate\View\View;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Manajemen Guru')]
class GuruManagement extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = '';
    public bool $showModal = false;
    public bool $showDeleteModal = false;
    public bool $showSyncModal = false;
    public bool $showDetailModal = false;
    public ?int $editingId = null;
    public ?int $deletingId = null;
    public ?Guru $viewingGuru = null;

    // Form fields
    public ?string $nip = null;
    public ?string $nuptk = null;
    public ?string $npk = null;
    public string $nik = '';
    public ?string $front_title = null;
    public string $full_name = '';
    public ?string $back_title = null;
    public string $gender = 'L';
    public ?string $pob = null;
    public ?string $dob = null;
    public ?string $phone_number = null;
    public ?string $address = null;
    public string $status_pegawai = 'GTY';
    public bool $is_active = true;

    // Sync
    public bool $isSyncing = false;
    public string $selectedApiSource = '';
    public array $apiSources = [
        'guru-mi' => 'Guru MI',
        'guru-smp' => 'Guru SMP',
    ];
    public array $syncResult = [
        'success' => false,
        'message' => '',
        'created' => 0,
        'updated' => 0,
        'failed' => 0,
        'errors' => [],
    ];

    protected function rules(): array
    {
        return [
            'nip' => 'nullable|string|max:30',
            'nuptk' => 'nullable|string|max:30',
            'npk' => 'nullable|string|max:30',
            'nik' => 'required|string|max:16|unique:gurus,nik' . ($this->editingId ? ',' . $this->editingId : ''),
            'front_title' => 'nullable|string|max:20',
            'full_name' => 'required|string|max:255',
            'back_title' => 'nullable|string|max:20',
            'gender' => 'required|in:L,P',
            'pob' => 'nullable|string|max:255',
            'dob' => 'nullable|date',
            'phone_number' => 'nullable|string|max:15',
            'address' => 'nullable|string',
            'status_pegawai' => 'required|in:GTY,GTT,PNS',
            'is_active' => 'boolean',
        ];
    }

    protected array $messages = [
        'nik.required' => 'NIK wajib diisi.',
        'nik.unique' => 'NIK sudah digunakan.',
        'full_name.required' => 'Nama lengkap wajib diisi.',
        'gender.required' => 'Jenis kelamin wajib dipilih.',
    ];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatusFilter(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        $this->resetFormFields();
        $this->showModal = true;
    }

    public function edit(int $id): void
    {
        $guru = Guru::findOrFail($id);
        $this->editingId = $guru->id;
        $this->nip = $guru->nip;
        $this->nuptk = $guru->nuptk;
        $this->npk = $guru->npk;
        $this->nik = $guru->nik;
        $this->front_title = $guru->front_title;
        $this->full_name = $guru->full_name;
        $this->back_title = $guru->back_title;
        $this->gender = $guru->gender;
        $this->pob = $guru->pob;
        $this->dob = $guru->dob?->format('Y-m-d');
        $this->phone_number = $guru->phone_number;
        $this->address = $guru->address;
        $this->status_pegawai = $guru->status_pegawai;
        $this->is_active = $guru->is_active;
        $this->showModal = true;
    }

    public function viewDetail(int $id): void
    {
        $this->viewingGuru = Guru::findOrFail($id);
        $this->showDetailModal = true;
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'nip' => $this->nip ?: null,
            'nuptk' => $this->nuptk ?: null,
            'npk' => $this->npk ?: null,
            'nik' => $this->nik,
            'front_title' => $this->front_title ?: null,
            'full_name' => $this->full_name,
            'back_title' => $this->back_title ?: null,
            'gender' => $this->gender,
            'pob' => $this->pob,
            'dob' => $this->dob,
            'phone_number' => $this->phone_number,
            'address' => $this->address,
            'status_pegawai' => $this->status_pegawai,
            'is_active' => $this->is_active,
        ];

        if ($this->editingId) {
            Guru::findOrFail($this->editingId)->update($data);
            session()->flash('success', 'Guru berhasil diperbarui.');
        } else {
            Guru::create($data);
            session()->flash('success', 'Guru berhasil ditambahkan.');
        }

        $this->closeModal();
    }

    public function confirmDelete(int $id): void
    {
        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        if ($this->deletingId) {
            Guru::findOrFail($this->deletingId)->delete();
            session()->flash('success', 'Guru berhasil dihapus.');
        }
        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    public function openSyncModal(): void
    {
        $this->syncResult = [
            'success' => false,
            'message' => '',
            'created' => 0,
            'updated' => 0,
            'failed' => 0,
            'errors' => [],
        ];
        $this->isSyncing = false;
        $this->selectedApiSource = '';
        $this->showSyncModal = true;
    }

    public function syncFromApi(): void
    {
        if (empty($this->selectedApiSource)) {
            $this->syncResult['message'] = 'Silahkan pilih sumber data terlebih dahulu.';
            return;
        }

        $this->isSyncing = true;
        $this->syncResult = [
            'success' => false,
            'message' => '',
            'created' => 0,
            'updated' => 0,
            'failed' => 0,
            'errors' => [],
        ];

        try {
            $created = 0;
            $updated = 0;
            $failed = 0;
            $errors = [];

            // Handle pagination - start from page 1
            $page = 1;
            $hasMorePages = true;
            $apiBaseUrl = env('SYNC_API_BASE_URL', 'https://datainduk.ypdhalmadani.sch.id');
            $baseUrl = "{$apiBaseUrl}/api/{$this->selectedApiSource}/all";

            while ($hasMorePages) {
                $response = Http::timeout(60)->get($baseUrl, ['page' => $page]);

                if (!$response->successful()) {
                    $this->syncResult['message'] = 'Gagal mengambil data dari API. Status: ' . $response->status();
                    $this->isSyncing = false;
                    return;
                }

                $data = $response->json();

                // Handle different API response structures (Laravel pagination or plain array)
                $gurus = $data['data'] ?? $data;

                if (!is_array($gurus)) {
                    $this->syncResult['message'] = 'Format response API tidak valid.';
                    $this->isSyncing = false;
                    return;
                }

                // Process each guru
                foreach ($gurus as $guruData) {
                    try {
                        $nik = $guruData['nik'] ?? null;
                        $fullName = $guruData['full_name'] ?? $guruData['nama'] ?? $guruData['name'] ?? null;
                        $gender = $guruData['gender'] ?? $guruData['jenis_kelamin'] ?? null;

                        // Normalize gender
                        if ($gender) {
                            if (in_array(strtolower($gender), ['laki-laki', 'male', 'l'])) {
                                $gender = 'L';
                            } elseif (in_array(strtolower($gender), ['perempuan', 'female', 'p'])) {
                                $gender = 'P';
                            }
                        }

                        if (!$nik || !$fullName) {
                            $failed++;
                            $errors[] = "Data tidak lengkap: NIK={$nik}, Nama={$fullName}";
                            continue;
                        }

                        $syncData = [
                            'nip' => $guruData['nip'] ?? null,
                            'nuptk' => $guruData['nuptk'] ?? null,
                            'npk' => $guruData['npk'] ?? null,
                            'nik' => $nik,
                            'front_title' => $guruData['front_title'] ?? $guruData['gelar_depan'] ?? null,
                            'full_name' => $fullName,
                            'back_title' => $guruData['back_title'] ?? $guruData['gelar_belakang'] ?? null,
                            'gender' => $gender ?? 'L',
                            'pob' => $guruData['pob'] ?? $guruData['tempat_lahir'] ?? null,
                            'dob' => $guruData['dob'] ?? $guruData['tanggal_lahir'] ?? null,
                            'phone_number' => $guruData['phone_number'] ?? $guruData['no_telepon'] ?? null,
                            'address' => $guruData['address'] ?? $guruData['alamat'] ?? null,
                            'status_pegawai' => $guruData['status_pegawai'] ?? 'GTY',
                            'is_active' => $guruData['is_active'] ?? true,
                        ];

                        // Try to find existing guru by NIK or NUPTK
                        $existingGuru = null;
                        if ($nik) {
                            $existingGuru = Guru::where('nik', $nik)->first();
                        }
                        if (!$existingGuru && !empty($guruData['nuptk'])) {
                            $existingGuru = Guru::where('nuptk', $guruData['nuptk'])->first();
                        }

                        if ($existingGuru) {
                            $existingGuru->update($syncData);
                            $updated++;
                        } else {
                            Guru::create($syncData);
                            $created++;
                        }
                    } catch (\Exception $e) {
                        $failed++;
                        $errors[] = "Error: " . $e->getMessage();
                    }
                }

                // Check if there are more pages (Laravel pagination format)
                $lastPage = $data['last_page'] ?? 1;
                $currentPage = $data['current_page'] ?? $page;
                $nextPageUrl = $data['next_page_url'] ?? null;

                if ($nextPageUrl || $currentPage < $lastPage) {
                    $page++;
                } else {
                    $hasMorePages = false;
                }

                // Safety check - prevent infinite loop
                if ($page > 1000) {
                    $hasMorePages = false;
                }
            }

            $this->syncResult = [
                'success' => true,
                'message' => 'Sinkronisasi selesai.',
                'created' => $created,
                'updated' => $updated,
                'failed' => $failed,
                'errors' => array_slice($errors, 0, 10),
            ];

            if ($created > 0 || $updated > 0) {
                session()->flash('success', "Sync berhasil: {$created} guru baru, {$updated} diperbarui.");
            }

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            $this->syncResult['message'] = 'Tidak dapat terhubung ke API. Pastikan server API berjalan.';
            Log::error('Sync API Error: ' . $e->getMessage());
        } catch (\Exception $e) {
            $this->syncResult['message'] = 'Terjadi kesalahan: ' . $e->getMessage();
            Log::error('Sync API Error: ' . $e->getMessage());
        }

        $this->isSyncing = false;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->showDeleteModal = false;
        $this->showSyncModal = false;
        $this->showDetailModal = false;
        $this->resetFormFields();
        $this->resetValidation();
    }

    protected function resetFormFields(): void
    {
        $this->editingId = null;
        $this->deletingId = null;
        $this->viewingGuru = null;
        $this->nip = null;
        $this->nuptk = null;
        $this->npk = null;
        $this->nik = '';
        $this->front_title = null;
        $this->full_name = '';
        $this->back_title = null;
        $this->gender = 'L';
        $this->pob = null;
        $this->dob = null;
        $this->phone_number = null;
        $this->address = null;
        $this->status_pegawai = 'GTY';
        $this->is_active = true;
    }

    public function render(): View
    {
        $gurus = Guru::query()
            ->when($this->search, fn($q) => $q->where('full_name', 'like', "%{$this->search}%")
                ->orWhere('nik', 'like', "%{$this->search}%")
                ->orWhere('nip', 'like', "%{$this->search}%")
                ->orWhere('nuptk', 'like', "%{$this->search}%"))
            ->when($this->statusFilter !== '', fn($q) => $q->where('is_active', $this->statusFilter === '1'))
            ->orderBy('full_name')
            ->paginate(10);

        return view('livewire.admin.guru-management', compact('gurus'));
    }
}
