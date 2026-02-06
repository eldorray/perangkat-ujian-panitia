<div>
    <x-slot name="header">Manajemen Siswa</x-slot>

    @if (session('success'))
        <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-800">{{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="p-6 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex gap-4 items-center">
                    <div class="relative">
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari siswa..."
                            class="input pl-10 w-full sm:w-80">
                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <select wire:model.live="statusFilter" class="input w-32">
                        <option value="">Semua</option>
                        <option value="Aktif">Aktif</option>
                        <option value="Tidak Aktif">Tidak Aktif</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button wire:click="openSyncModal" class="btn btn-secondary">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                            </path>
                        </svg>
                        Sync API
                    </button>
                    <button wire:click="create" class="btn btn-primary">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                            </path>
                        </svg>
                        Tambah Siswa
                    </button>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Lengkap</th>
                        <th>NISN</th>
                        <th>Tingkat/Rombel</th>
                        <th>L/P</th>
                        <th>Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswas as $index => $siswa)
                        <tr wire:key="siswa-{{ $siswa->id }}">
                            <td>{{ $siswas->firstItem() + $index }}</td>
                            <td class="font-medium">{{ $siswa->nama_lengkap }}</td>
                            <td class="font-mono text-sm">{{ $siswa->nisn ?? '-' }}</td>
                            <td>{{ $siswa->tingkat_rombel ?? '-' }}</td>
                            <td>{{ $siswa->jenis_kelamin_label ?? '-' }}</td>
                            <td>
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $siswa->status === 'Aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $siswa->status }}
                                </span>
                            </td>
                            <td class="text-right">
                                <button wire:click="viewDetail({{ $siswa->id }})"
                                    class="btn btn-ghost btn-sm">Detail</button>
                                <button wire:click="edit({{ $siswa->id }})"
                                    class="btn btn-ghost btn-sm">Edit</button>
                                <button wire:click="confirmDelete({{ $siswa->id }})"
                                    class="btn btn-ghost btn-sm text-red-600">Hapus</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-8 text-gray-500">Tidak ada data siswa.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($siswas->hasPages())
            <div class="p-6 border-t">{{ $siswas->links() }}</div>
        @endif
    </div>

    <!-- Create/Edit Modal -->
    @if ($showModal)
        <template x-teleport="#modal-portal">
            <div class="fixed inset-0 z-[9999] overflow-y-auto">
                <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" wire:click="closeModal"></div>
                <div class="fixed inset-0 flex items-center justify-center p-4">
                    <div class="relative w-full max-w-3xl bg-white rounded-2xl shadow-2xl max-h-[90vh] overflow-y-auto">
                        <form wire:submit="save">
                            <div class="border-b border-gray-200 px-6 py-4 sticky top-0 bg-white rounded-t-2xl">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $editingId ? 'Edit' : 'Tambah' }}
                                    Siswa</h3>
                            </div>
                            <div class="px-6 py-4 space-y-6">
                                <!-- Data Pribadi -->
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-700 mb-3 pb-2 border-b">Data Pribadi</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="md:col-span-2">
                                            <label class="block text-sm font-medium mb-1">Nama Lengkap <span
                                                    class="text-red-500">*</span></label>
                                            <input type="text" wire:model="nama_lengkap" class="input w-full"
                                                placeholder="Nama lengkap siswa">
                                            @error('nama_lengkap')
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium mb-1">NISN</label>
                                            <input type="text" wire:model="nisn" class="input w-full"
                                                placeholder="Nomor Induk Siswa Nasional">
                                            @error('nisn')
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium mb-1">NIK</label>
                                            <input type="text" wire:model="nik" class="input w-full"
                                                placeholder="Nomor Induk Kependudukan">
                                            @error('nik')
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium mb-1">Tempat Lahir</label>
                                            <input type="text" wire:model="tempat_lahir" class="input w-full"
                                                placeholder="Tempat lahir">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium mb-1">Tanggal Lahir</label>
                                            <input type="date" wire:model="tanggal_lahir" class="input w-full">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium mb-1">Umur</label>
                                            <input type="number" wire:model="umur" class="input w-full"
                                                placeholder="Umur" min="1" max="100">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium mb-1">Jenis Kelamin</label>
                                            <select wire:model="jenis_kelamin" class="input w-full">
                                                <option value="L">Laki-laki</option>
                                                <option value="P">Perempuan</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium mb-1">Tingkat/Rombel</label>
                                            <input type="text" wire:model="tingkat_rombel" class="input w-full"
                                                placeholder="Contoh: Kelas 1 - KELAS 1A">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium mb-1">Status</label>
                                            <select wire:model="status" class="input w-full">
                                                <option value="Aktif">Aktif</option>
                                                <option value="Tidak Aktif">Tidak Aktif</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium mb-1">No. Telepon</label>
                                            <input type="text" wire:model="no_telepon" class="input w-full"
                                                placeholder="Nomor telepon">
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="block text-sm font-medium mb-1">Alamat</label>
                                            <textarea wire:model="alamat" class="input w-full" rows="2" placeholder="Alamat lengkap"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Data Khusus -->
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-700 mb-3 pb-2 border-b">Data Khusus</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium mb-1">Kebutuhan Khusus</label>
                                            <input type="text" wire:model="kebutuhan_khusus" class="input w-full"
                                                placeholder="Kebutuhan khusus (jika ada)">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium mb-1">Disabilitas</label>
                                            <input type="text" wire:model="disabilitas" class="input w-full"
                                                placeholder="Disabilitas (jika ada)">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium mb-1">Nomor KIP/PIP</label>
                                            <input type="text" wire:model="nomor_kip_pip" class="input w-full"
                                                placeholder="Nomor KIP/PIP">
                                        </div>
                                    </div>
                                </div>

                                <!-- Data Orang Tua/Wali -->
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-700 mb-3 pb-2 border-b">Data Orang
                                        Tua/Wali</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium mb-1">Nama Ayah Kandung</label>
                                            <input type="text" wire:model="nama_ayah_kandung" class="input w-full"
                                                placeholder="Nama ayah kandung">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium mb-1">Nama Ibu Kandung</label>
                                            <input type="text" wire:model="nama_ibu_kandung" class="input w-full"
                                                placeholder="Nama ibu kandung">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium mb-1">Nama Wali</label>
                                            <input type="text" wire:model="nama_wali" class="input w-full"
                                                placeholder="Nama wali (jika ada)">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="border-t border-gray-200 bg-gray-50 px-6 py-4 flex justify-end gap-3 rounded-b-2xl sticky bottom-0">
                                <button type="button" wire:click="closeModal"
                                    class="btn btn-secondary">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </template>
    @endif

    <!-- Detail Modal -->
    @if ($showDetailModal && $viewingSiswa)
        <template x-teleport="#modal-portal">
            <div class="fixed inset-0 z-[9999] overflow-y-auto">
                <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" wire:click="closeModal"></div>
                <div class="fixed inset-0 flex items-center justify-center p-4">
                    <div
                        class="relative w-full max-w-2xl bg-white rounded-2xl shadow-2xl max-h-[90vh] overflow-y-auto">
                        <div class="border-b border-gray-200 px-6 py-4 sticky top-0 bg-white rounded-t-2xl">
                            <h3 class="text-lg font-semibold text-gray-900">Detail Siswa</h3>
                        </div>
                        <div class="px-6 py-4 space-y-6">
                            <!-- Data Pribadi -->
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700 mb-3 pb-2 border-b">Data Pribadi</h4>
                                <dl class="grid grid-cols-2 gap-x-4 gap-y-3 text-sm">
                                    <div>
                                        <dt class="text-gray-500">Nama Lengkap</dt>
                                        <dd class="font-medium">{{ $viewingSiswa->nama_lengkap }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-gray-500">NISN</dt>
                                        <dd class="font-mono">{{ $viewingSiswa->nisn ?? '-' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-gray-500">NIK</dt>
                                        <dd class="font-mono">{{ $viewingSiswa->nik ?? '-' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-gray-500">Tempat, Tanggal Lahir</dt>
                                        <dd>{{ $viewingSiswa->tempat_lahir ?? '-' }},
                                            {{ $viewingSiswa->tanggal_lahir?->format('d M Y') ?? '-' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-gray-500">Umur</dt>
                                        <dd>{{ $viewingSiswa->umur ?? '-' }} tahun</dd>
                                    </div>
                                    <div>
                                        <dt class="text-gray-500">Jenis Kelamin</dt>
                                        <dd>{{ $viewingSiswa->jenis_kelamin_label }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-gray-500">Tingkat/Rombel</dt>
                                        <dd>{{ $viewingSiswa->tingkat_rombel ?? '-' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-gray-500">Status</dt>
                                        <dd><span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $viewingSiswa->status === 'Aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">{{ $viewingSiswa->status }}</span>
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-gray-500">No. Telepon</dt>
                                        <dd>{{ $viewingSiswa->no_telepon ?? '-' }}</dd>
                                    </div>
                                    <div class="col-span-2">
                                        <dt class="text-gray-500">Alamat</dt>
                                        <dd>{{ $viewingSiswa->alamat ?? '-' }}</dd>
                                    </div>
                                </dl>
                            </div>

                            <!-- Data Khusus -->
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700 mb-3 pb-2 border-b">Data Khusus</h4>
                                <dl class="grid grid-cols-2 gap-x-4 gap-y-3 text-sm">
                                    <div>
                                        <dt class="text-gray-500">Kebutuhan Khusus</dt>
                                        <dd>{{ $viewingSiswa->kebutuhan_khusus ?? '-' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-gray-500">Disabilitas</dt>
                                        <dd>{{ $viewingSiswa->disabilitas ?? '-' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-gray-500">Nomor KIP/PIP</dt>
                                        <dd>{{ $viewingSiswa->nomor_kip_pip ?? '-' }}</dd>
                                    </div>
                                </dl>
                            </div>

                            <!-- Data Orang Tua/Wali -->
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700 mb-3 pb-2 border-b">Data Orang Tua/Wali
                                </h4>
                                <dl class="grid grid-cols-2 gap-x-4 gap-y-3 text-sm">
                                    <div>
                                        <dt class="text-gray-500">Nama Ayah Kandung</dt>
                                        <dd>{{ $viewingSiswa->nama_ayah_kandung ?? '-' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-gray-500">Nama Ibu Kandung</dt>
                                        <dd>{{ $viewingSiswa->nama_ibu_kandung ?? '-' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-gray-500">Nama Wali</dt>
                                        <dd>{{ $viewingSiswa->nama_wali ?? '-' }}</dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                        <div
                            class="border-t border-gray-200 bg-gray-50 px-6 py-4 flex justify-end gap-3 rounded-b-2xl sticky bottom-0">
                            <button type="button" wire:click="closeModal" class="btn btn-secondary">Tutup</button>
                            <button type="button" wire:click="edit({{ $viewingSiswa->id }})"
                                class="btn btn-primary">Edit</button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    @endif

    <!-- Delete Modal -->
    @if ($showDeleteModal)
        <template x-teleport="#modal-portal">
            <div class="fixed inset-0 z-[9999] overflow-y-auto">
                <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" wire:click="closeModal"></div>
                <div class="fixed inset-0 flex items-center justify-center p-4">
                    <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl p-6">
                        <h3 class="text-lg font-semibold mb-2">Hapus Siswa</h3>
                        <p class="text-gray-500 mb-4">Yakin ingin menghapus data siswa ini?</p>
                        <div class="flex justify-end gap-3">
                            <button wire:click="closeModal" class="btn btn-secondary">Batal</button>
                            <button wire:click="delete" class="btn btn-destructive">Hapus</button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    @endif

    <!-- Sync API Modal -->
    @if ($showSyncModal)
        <template x-teleport="#modal-portal">
            <div class="fixed inset-0 z-[9999] overflow-y-auto">
                <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" wire:click="closeModal"></div>
                <div class="fixed inset-0 flex items-center justify-center p-4">
                    <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl">
                        <div class="border-b border-gray-200 px-6 py-4">
                            <h3 class="text-lg font-semibold text-gray-900">Sync Data Siswa dari API</h3>
                        </div>
                        <div class="px-6 py-4 space-y-4">
                            <!-- Source Selection -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Sumber Data</label>
                                <select wire:model.live="selectedApiSource" class="input w-full">
                                    <option value="">-- Pilih Sumber Data --</option>
                                    @foreach ($apiSources as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="p-4 bg-blue-50 rounded-xl border border-blue-200">
                                <p class="text-sm text-blue-800 mb-2">Informasi:</p>
                                <ul class="text-sm text-blue-700 list-disc list-inside space-y-1">
                                    <li>Data akan diambil dari API:
                                        @if ($selectedApiSource)
                                            <code
                                                class="bg-blue-100 px-1 rounded">{{ env('SYNC_API_BASE_URL', 'http://localhost:8000') }}/api/{{ $selectedApiSource }}/all</code>
                                        @else
                                            <code class="bg-gray-100 px-1 rounded text-gray-500">Pilih sumber data
                                                terlebih dahulu</code>
                                        @endif
                                    </li>
                                    <li>Siswa yang sudah ada (berdasarkan NISN/NIK) akan diperbarui</li>
                                    <li>Siswa baru akan ditambahkan ke database</li>
                                    <li>Pastikan server API aktif sebelum sync</li>
                                </ul>
                            </div>

                            @if ($syncResult['message'])
                                <div
                                    class="p-4 rounded-xl border {{ $syncResult['success'] ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200' }}">
                                    <p
                                        class="text-sm font-medium {{ $syncResult['success'] ? 'text-green-800' : 'text-red-800' }}">
                                        {{ $syncResult['message'] }}</p>

                                    @if ($syncResult['success'])
                                        <div class="mt-3 grid grid-cols-3 gap-4 text-center">
                                            <div class="p-3 bg-white rounded-lg shadow-sm">
                                                <div class="text-2xl font-bold text-green-600">
                                                    {{ $syncResult['created'] }}</div>
                                                <div class="text-xs text-gray-500">Siswa Baru</div>
                                            </div>
                                            <div class="p-3 bg-white rounded-lg shadow-sm">
                                                <div class="text-2xl font-bold text-blue-600">
                                                    {{ $syncResult['updated'] }}</div>
                                                <div class="text-xs text-gray-500">Diperbarui</div>
                                            </div>
                                            <div class="p-3 bg-white rounded-lg shadow-sm">
                                                <div class="text-2xl font-bold text-red-600">
                                                    {{ $syncResult['failed'] }}</div>
                                                <div class="text-xs text-gray-500">Gagal</div>
                                            </div>
                                        </div>
                                    @endif

                                    @if (count($syncResult['errors']) > 0)
                                        <div class="mt-3">
                                            <p class="text-sm font-medium text-red-700 mb-1">Detail Error:</p>
                                            <ul
                                                class="text-xs text-red-600 list-disc list-inside max-h-32 overflow-y-auto">
                                                @foreach ($syncResult['errors'] as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                        <div
                            class="border-t border-gray-200 bg-gray-50 px-6 py-4 flex justify-end gap-3 rounded-b-2xl">
                            <button type="button" wire:click="closeModal" class="btn btn-secondary">Tutup</button>
                            <button type="button" wire:click="syncFromApi"
                                class="btn btn-primary {{ !$selectedApiSource ? 'opacity-50 cursor-not-allowed' : '' }}"
                                wire:loading.attr="disabled" wire:target="syncFromApi"
                                {{ !$selectedApiSource ? 'disabled' : '' }}>
                                <span wire:loading.remove wire:target="syncFromApi" class="flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                        </path>
                                    </svg>
                                    Sync Sekarang
                                </span>
                                <span wire:loading wire:target="syncFromApi" class="flex items-center gap-2">
                                    <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                    Menyinkronkan...
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    @endif

</div>
