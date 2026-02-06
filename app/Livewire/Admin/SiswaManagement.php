<?php

namespace App\Livewire\Admin;

use App\Models\Siswa;
use Illuminate\View\View;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Manajemen Siswa')]
class SiswaManagement extends Component
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
    public ?Siswa $viewingSiswa = null;

    // Form fields
    public string $nama_lengkap = '';
    public ?string $nisn = null;
    public ?string $nik = null;
    public ?string $tempat_lahir = null;
    public ?string $tanggal_lahir = null;
    public ?string $tingkat_rombel = null;
    public ?int $umur = null;
    public string $status = 'Aktif';
    public ?string $jenis_kelamin = 'L';
    public ?string $alamat = null;
    public ?string $no_telepon = null;
    public ?string $kebutuhan_khusus = null;
    public ?string $disabilitas = null;
    public ?string $nomor_kip_pip = null;
    public ?string $nama_ayah_kandung = null;
    public ?string $nama_ibu_kandung = null;
    public ?string $nama_wali = null;

    // Sync
    public bool $isSyncing = false;
    public string $selectedApiSource = '';
    public array $apiSources = [
        'siswa-mi' => 'Siswa MI',
        'siswa-smp' => 'Siswa SMP',
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
            'nama_lengkap' => 'required|string|max:255',
            'nisn' => 'nullable|string|max:20|unique:siswas,nisn' . ($this->editingId ? ',' . $this->editingId : ''),
            'nik' => 'nullable|string|max:20|unique:siswas,nik' . ($this->editingId ? ',' . $this->editingId : ''),
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'tingkat_rombel' => 'nullable|string|max:255',
            'umur' => 'nullable|integer|min:1|max:100',
            'status' => 'required|in:Aktif,Tidak Aktif',
            'jenis_kelamin' => 'nullable|in:L,P',
            'alamat' => 'nullable|string',
            'no_telepon' => 'nullable|string|max:20',
            'kebutuhan_khusus' => 'nullable|string|max:255',
            'disabilitas' => 'nullable|string|max:255',
            'nomor_kip_pip' => 'nullable|string|max:50',
            'nama_ayah_kandung' => 'nullable|string|max:255',
            'nama_ibu_kandung' => 'nullable|string|max:255',
            'nama_wali' => 'nullable|string|max:255',
        ];
    }

    protected array $messages = [
        'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
        'nisn.unique' => 'NISN sudah digunakan.',
        'nik.unique' => 'NIK sudah digunakan.',
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
        $siswa = Siswa::findOrFail($id);
        $this->editingId = $siswa->id;
        $this->nama_lengkap = $siswa->nama_lengkap;
        $this->nisn = $siswa->nisn;
        $this->nik = $siswa->nik;
        $this->tempat_lahir = $siswa->tempat_lahir;
        $this->tanggal_lahir = $siswa->tanggal_lahir?->format('Y-m-d');
        $this->tingkat_rombel = $siswa->tingkat_rombel;
        $this->umur = $siswa->umur;
        $this->status = $siswa->status;
        $this->jenis_kelamin = $siswa->jenis_kelamin;
        $this->alamat = $siswa->alamat;
        $this->no_telepon = $siswa->no_telepon;
        $this->kebutuhan_khusus = $siswa->kebutuhan_khusus;
        $this->disabilitas = $siswa->disabilitas;
        $this->nomor_kip_pip = $siswa->nomor_kip_pip;
        $this->nama_ayah_kandung = $siswa->nama_ayah_kandung;
        $this->nama_ibu_kandung = $siswa->nama_ibu_kandung;
        $this->nama_wali = $siswa->nama_wali;
        $this->showModal = true;
    }

    public function viewDetail(int $id): void
    {
        $this->viewingSiswa = Siswa::findOrFail($id);
        $this->showDetailModal = true;
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'nama_lengkap' => $this->nama_lengkap,
            'nisn' => $this->nisn ?: null,
            'nik' => $this->nik ?: null,
            'tempat_lahir' => $this->tempat_lahir,
            'tanggal_lahir' => $this->tanggal_lahir,
            'tingkat_rombel' => $this->tingkat_rombel,
            'umur' => $this->umur,
            'status' => $this->status,
            'jenis_kelamin' => $this->jenis_kelamin,
            'alamat' => $this->alamat,
            'no_telepon' => $this->no_telepon,
            'kebutuhan_khusus' => $this->kebutuhan_khusus,
            'disabilitas' => $this->disabilitas,
            'nomor_kip_pip' => $this->nomor_kip_pip,
            'nama_ayah_kandung' => $this->nama_ayah_kandung,
            'nama_ibu_kandung' => $this->nama_ibu_kandung,
            'nama_wali' => $this->nama_wali,
        ];

        if ($this->editingId) {
            Siswa::findOrFail($this->editingId)->update($data);
            session()->flash('success', 'Siswa berhasil diperbarui.');
        } else {
            Siswa::create($data);
            session()->flash('success', 'Siswa berhasil ditambahkan.');
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
            Siswa::findOrFail($this->deletingId)->delete();
            session()->flash('success', 'Siswa berhasil dihapus.');
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
                $siswas = $data['data'] ?? $data;

                if (!is_array($siswas)) {
                    $this->syncResult['message'] = 'Format response API tidak valid.';
                    $this->isSyncing = false;
                    return;
                }

                // Process each siswa
                foreach ($siswas as $siswaData) {
                    try {
                        // Map API fields to local fields
                        $namaLengkap = $siswaData['nama_lengkap'] ?? $siswaData['nama'] ?? $siswaData['name'] ?? null;
                        $nisn = $siswaData['nisn'] ?? null;
                        $nik = $siswaData['nik'] ?? null;
                        $jenisKelamin = $siswaData['jenis_kelamin'] ?? $siswaData['gender'] ?? null;

                        // Normalize jenis_kelamin
                        if ($jenisKelamin) {
                            if (in_array(strtolower($jenisKelamin), ['laki-laki', 'male', 'l'])) {
                                $jenisKelamin = 'L';
                            } elseif (in_array(strtolower($jenisKelamin), ['perempuan', 'female', 'p'])) {
                                $jenisKelamin = 'P';
                            }
                        }

                        if (!$namaLengkap) {
                            $failed++;
                            $errors[] = "Data tidak lengkap: Nama={$namaLengkap}";
                            continue;
                        }

                        // Prepare data for sync
                        $syncData = [
                            'nama_lengkap' => $namaLengkap,
                            'nisn' => $nisn ?: null,
                            'nik' => $nik ?: null,
                            'tempat_lahir' => $siswaData['tempat_lahir'] ?? null,
                            'tanggal_lahir' => $siswaData['tanggal_lahir'] ?? null,
                            'tingkat_rombel' => $siswaData['tingkat_rombel'] ?? $siswaData['kelas'] ?? null,
                            'umur' => isset($siswaData['umur']) ? (int) $siswaData['umur'] : null,
                            'status' => $siswaData['status'] ?? 'Aktif',
                            'jenis_kelamin' => $jenisKelamin,
                            'alamat' => $siswaData['alamat'] ?? null,
                            'no_telepon' => $siswaData['no_telepon'] ?? $siswaData['telepon'] ?? null,
                            'kebutuhan_khusus' => $siswaData['kebutuhan_khusus'] ?? null,
                            'disabilitas' => $siswaData['disabilitas'] ?? null,
                            'nomor_kip_pip' => $siswaData['nomor_kip_pip'] ?? null,
                            'nama_ayah_kandung' => $siswaData['nama_ayah_kandung'] ?? $siswaData['ayah'] ?? null,
                            'nama_ibu_kandung' => $siswaData['nama_ibu_kandung'] ?? $siswaData['ibu'] ?? null,
                            'nama_wali' => $siswaData['nama_wali'] ?? $siswaData['wali'] ?? null,
                        ];

                        // Try to find existing siswa by NISN or NIK
                        $existingSiswa = null;
                        if ($nisn) {
                            $existingSiswa = Siswa::where('nisn', $nisn)->first();
                        }
                        if (!$existingSiswa && $nik) {
                            $existingSiswa = Siswa::where('nik', $nik)->first();
                        }

                        if ($existingSiswa) {
                            $existingSiswa->update($syncData);
                            $updated++;
                        } else {
                            Siswa::create($syncData);
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
                session()->flash('success', "Sync berhasil: {$created} siswa baru, {$updated} diperbarui.");
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
        $this->viewingSiswa = null;
        $this->nama_lengkap = '';
        $this->nisn = null;
        $this->nik = null;
        $this->tempat_lahir = null;
        $this->tanggal_lahir = null;
        $this->tingkat_rombel = null;
        $this->umur = null;
        $this->status = 'Aktif';
        $this->jenis_kelamin = 'L';
        $this->alamat = null;
        $this->no_telepon = null;
        $this->kebutuhan_khusus = null;
        $this->disabilitas = null;
        $this->nomor_kip_pip = null;
        $this->nama_ayah_kandung = null;
        $this->nama_ibu_kandung = null;
        $this->nama_wali = null;
    }

    public function render(): View
    {
        $siswas = Siswa::query()
            ->when($this->search, fn($q) => $q->where('nama_lengkap', 'like', "%{$this->search}%")
                ->orWhere('nisn', 'like', "%{$this->search}%")
                ->orWhere('nik', 'like', "%{$this->search}%"))
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->orderBy('nama_lengkap')
            ->paginate(10);

        return view('livewire.admin.siswa-management', compact('siswas'));
    }
}
