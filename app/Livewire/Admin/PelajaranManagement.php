<?php

namespace App\Livewire\Admin;

use App\Models\Pelajaran;
use Illuminate\View\View;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Manajemen Pelajaran')]
class PelajaranManagement extends Component
{
    use WithPagination;

    public string $search = '';
    public bool $showModal = false;
    public bool $showDeleteModal = false;
    public bool $showSyncModal = false;
    public ?int $editingId = null;
    public ?int $deletingId = null;

    // Form fields
    public ?string $kode_mapel = null;
    public string $nama_mapel = '';
    public ?int $jam_per_minggu = 2;
    public string $kelompok = 'Umum';
    public ?string $jurusan = null;
    public bool $is_active = true;

    // Sync
    public bool $isSyncing = false;
    public string $selectedApiSource = '';
    public array $apiSources = [
        'mapel-mi' => 'Mapel MI',
        'mapel-smp' => 'Mapel SMP',
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
            'kode_mapel' => 'nullable|string|max:20|unique:pelajarans,kode_mapel' . ($this->editingId ? ',' . $this->editingId : ''),
            'nama_mapel' => 'required|string|max:255',
            'jam_per_minggu' => 'nullable|integer|min:1|max:20',
            'kelompok' => 'required|in:PAI,Umum',
            'jurusan' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ];
    }

    protected array $messages = [
        'kode_mapel.unique' => 'Kode mapel sudah digunakan.',
        'nama_mapel.required' => 'Nama mapel wajib diisi.',
        'kelompok.required' => 'Kelompok wajib dipilih.',
    ];

    public function updatedSearch(): void
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
        $pelajaran = Pelajaran::findOrFail($id);
        $this->editingId = $pelajaran->id;
        $this->kode_mapel = $pelajaran->kode_mapel;
        $this->nama_mapel = $pelajaran->nama_mapel;
        $this->jam_per_minggu = $pelajaran->jam_per_minggu;
        $this->kelompok = $pelajaran->kelompok;
        $this->jurusan = $pelajaran->jurusan;
        $this->is_active = $pelajaran->is_active;
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'kode_mapel' => $this->kode_mapel ?: null,
            'nama_mapel' => $this->nama_mapel,
            'jam_per_minggu' => $this->jam_per_minggu,
            'kelompok' => $this->kelompok,
            'jurusan' => $this->jurusan ?: null,
            'is_active' => $this->is_active,
        ];

        if ($this->editingId) {
            Pelajaran::findOrFail($this->editingId)->update($data);
            session()->flash('success', 'Mapel berhasil diperbarui.');
        } else {
            Pelajaran::create($data);
            session()->flash('success', 'Mapel berhasil ditambahkan.');
        }

        $this->closeModal();
    }

    protected function resetFormFields(): void
    {
        $this->editingId = null;
        $this->deletingId = null;
        $this->kode_mapel = null;
        $this->nama_mapel = '';
        $this->jam_per_minggu = 2;
        $this->kelompok = 'Umum';
        $this->jurusan = null;
        $this->is_active = true;
    }

    public function confirmDelete(int $id): void
    {
        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        if ($this->deletingId) {
            Pelajaran::findOrFail($this->deletingId)->delete();
            session()->flash('success', 'Pelajaran berhasil dihapus.');
        }
        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->showDeleteModal = false;
        $this->showSyncModal = false;
        $this->resetFormFields();
        $this->resetValidation();
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
            $apiBaseUrl = env('SYNC_API_BASE_URL', 'http://localhost:8000');
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
                $mapels = $data['data'] ?? $data;
                
                if (!is_array($mapels)) {
                    $this->syncResult['message'] = 'Format response API tidak valid.';
                    $this->isSyncing = false;
                    return;
                }

                // Process each mapel
                foreach ($mapels as $mapelData) {
                    try {
                        // Map API fields to local fields
                        $kodeMapel = $mapelData['kode_mapel'] ?? $mapelData['kode'] ?? $mapelData['code'] ?? null;
                        $namaMapel = $mapelData['nama_mapel'] ?? $mapelData['nama'] ?? $mapelData['name'] ?? null;

                        if (!$namaMapel) {
                            $failed++;
                            $errors[] = "Data tidak lengkap: Nama Mapel kosong";
                            continue;
                        }

                        // Prepare data for sync
                        $syncData = [
                            'kode_mapel' => $kodeMapel ?: null,
                            'nama_mapel' => $namaMapel,
                            'jam_per_minggu' => $mapelData['jam_per_minggu'] ?? 2,
                            'kelompok' => $mapelData['kelompok'] ?? 'Umum',
                            'jurusan' => $mapelData['jurusan'] ?? null,
                            'is_active' => $mapelData['is_active'] ?? true,
                        ];

                        // Try to find existing pelajaran by kode_mapel or nama_mapel
                        $existingPelajaran = null;
                        if ($kodeMapel) {
                            $existingPelajaran = Pelajaran::where('kode_mapel', $kodeMapel)->first();
                        }
                        if (!$existingPelajaran) {
                            $existingPelajaran = Pelajaran::where('nama_mapel', $namaMapel)->first();
                        }
                        
                        if ($existingPelajaran) {
                            $existingPelajaran->update($syncData);
                            $updated++;
                        } else {
                            Pelajaran::create($syncData);
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
                session()->flash('success', "Sync berhasil: {$created} mapel baru, {$updated} diperbarui.");
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

    public function render(): View
    {
        $pelajarans = Pelajaran::query()
            ->when($this->search, fn($q) => $q->where('nama_mapel', 'like', "%{$this->search}%")
                ->orWhere('kode_mapel', 'like', "%{$this->search}%"))
            ->orderBy('nama_mapel')
            ->paginate(10);

        return view('livewire.admin.pelajaran-management', compact('pelajarans'));
    }
}
