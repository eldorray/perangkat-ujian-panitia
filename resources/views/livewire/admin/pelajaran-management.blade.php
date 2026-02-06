<div>
    <x-slot name="header">Manajemen Pelajaran</x-slot>

    @if (session('success'))
        <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-800">{{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="p-6 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="relative">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari pelajaran..."
                        class="input pl-10 w-full sm:w-80">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <div class="flex gap-2">
                    <button wire:click="openSyncModal" class="btn btn-secondary">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                            </path>
                        </svg>
                        Sync dari API
                    </button>
                    <button wire:click="create" class="btn btn-primary">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                            </path>
                        </svg>
                        Tambah Pelajaran
                    </button>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama Mapel</th>
                        <th>Jam/Minggu</th>
                        <th>Kelompok</th>
                        <th>Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pelajarans as $index => $pelajaran)
                        <tr wire:key="pelajaran-{{ $pelajaran->id }}">
                            <td>{{ $pelajarans->firstItem() + $index }}</td>
                            <td class="font-mono">{{ $pelajaran->kode_mapel ?? '-' }}</td>
                            <td class="font-medium">{{ $pelajaran->nama_mapel }}</td>
                            <td>{{ $pelajaran->jam_per_minggu ?? 2 }}</td>
                            <td>
                                <span
                                    class="px-2 py-1 text-xs rounded-full {{ $pelajaran->kelompok === 'PAI' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ $pelajaran->kelompok }}
                                </span>
                            </td>
                            <td>
                                <span
                                    class="px-2 py-1 text-xs rounded-full {{ $pelajaran->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $pelajaran->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                            </td>
                            <td class="text-right">
                                <button wire:click="edit({{ $pelajaran->id }})"
                                    class="btn btn-ghost btn-sm">Edit</button>
                                <button wire:click="confirmDelete({{ $pelajaran->id }})"
                                    class="btn btn-ghost btn-sm text-red-600">Hapus</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-8 text-gray-500">Tidak ada data.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($pelajarans->hasPages())
            <div class="p-6 border-t">{{ $pelajarans->links() }}</div>
        @endif
    </div>

    @if ($showModal)
        <template x-teleport="#modal-portal">
            <div class="fixed inset-0 z-[9999] overflow-y-auto">
                <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" wire:click="closeModal"></div>
                <div class="fixed inset-0 flex items-center justify-center p-4">
                    <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl">
                        <form wire:submit="save">
                            <div class="border-b border-gray-200 px-6 py-4">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $editingId ? 'Edit' : 'Tambah' }}
                                    Mapel</h3>
                            </div>
                            <div class="px-6 py-4 space-y-4 max-h-[60vh] overflow-y-auto">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium mb-1">Kode Mapel</label>
                                        <input type="text" wire:model="kode_mapel" class="input w-full"
                                            placeholder="Contoh: MTK">
                                        @error('kode_mapel')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium mb-1">Jam/Minggu</label>
                                        <input type="number" wire:model="jam_per_minggu" class="input w-full"
                                            min="1" max="20">
                                        @error('jam_per_minggu')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Nama Mapel <span
                                            class="text-red-500">*</span></label>
                                    <input type="text" wire:model="nama_mapel" class="input w-full"
                                        placeholder="Contoh: Matematika">
                                    @error('nama_mapel')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium mb-1">Kelompok <span
                                                class="text-red-500">*</span></label>
                                        <select wire:model="kelompok" class="input w-full">
                                            <option value="Umum">Umum</option>
                                            <option value="PAI">PAI</option>
                                        </select>
                                        @error('kelompok')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium mb-1">Jurusan</label>
                                        <input type="text" wire:model="jurusan" class="input w-full"
                                            placeholder="Kosongkan jika umum">
                                        @error('jurusan')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div>
                                    <label class="flex items-center gap-2">
                                        <input type="checkbox" wire:model="is_active" class="rounded border-gray-300">
                                        <span class="text-sm font-medium">Aktif</span>
                                    </label>
                                </div>
                            </div>
                            <div
                                class="border-t border-gray-200 bg-gray-50 px-6 py-4 flex justify-end gap-3 rounded-b-2xl">
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

    @if ($showDeleteModal)
        <template x-teleport="#modal-portal">
            <div class="fixed inset-0 z-[9999] overflow-y-auto">
                <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" wire:click="closeModal"></div>
                <div class="fixed inset-0 flex items-center justify-center p-4">
                    <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl p-6">
                        <h3 class="text-lg font-semibold mb-2">Hapus Pelajaran</h3>
                        <p class="text-gray-500 mb-4">Yakin ingin menghapus data ini?</p>
                        <div class="flex justify-end gap-3">
                            <button wire:click="closeModal" class="btn btn-secondary">Batal</button>
                            <button wire:click="delete" class="btn btn-destructive">Hapus</button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    @endif

    @if ($showSyncModal)
        <template x-teleport="#modal-portal">
            <div class="fixed inset-0 z-[9999] overflow-y-auto">
                <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" wire:click="closeModal"></div>
                <div class="fixed inset-0 flex items-center justify-center p-4">
                    <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl">
                        <div class="border-b border-gray-200 px-6 py-4">
                            <h3 class="text-lg font-semibold text-gray-900">Sinkronisasi Mapel dari API</h3>
                        </div>
                        <div class="px-6 py-4 space-y-4">
                            <!-- API Source Selection -->
                            <div>
                                <label class="block text-sm font-medium mb-1">Pilih Sumber Data</label>
                                <select wire:model.live="selectedApiSource" class="input w-full"
                                    {{ $isSyncing ? 'disabled' : '' }}>
                                    <option value="">-- Pilih Sumber --</option>
                                    @foreach ($apiSources as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- API Endpoint Preview -->
                            @if ($selectedApiSource)
                                <div class="p-3 bg-gray-100 rounded-lg">
                                    <span class="text-xs text-gray-500">API Endpoint:</span>
                                    <p class="text-sm font-mono text-gray-700">
                                        {{ env('SYNC_API_BASE_URL', 'http://localhost:8000') }}/api/{{ $selectedApiSource }}/all
                                    </p>
                                </div>
                            @endif

                            <!-- Sync Result -->
                            @if ($syncResult['message'])
                                <div
                                    class="p-4 rounded-lg {{ $syncResult['success'] ? 'bg-green-50 text-green-800' : 'bg-red-50 text-red-800' }}">
                                    <p class="font-medium">{{ $syncResult['message'] }}</p>
                                    @if ($syncResult['success'])
                                        <div class="mt-2 text-sm space-y-1">
                                            <p>✓ Data baru ditambahkan: {{ $syncResult['created'] }}</p>
                                            <p>✓ Data diperbarui: {{ $syncResult['updated'] }}</p>
                                            @if ($syncResult['failed'] > 0)
                                                <p>✗ Gagal: {{ $syncResult['failed'] }}</p>
                                            @endif
                                        </div>
                                        @if (count($syncResult['errors']) > 0)
                                            <div class="mt-2">
                                                <p class="text-sm font-medium">Errors:</p>
                                                <ul class="text-xs list-disc list-inside">
                                                    @foreach ($syncResult['errors'] as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            @endif

                            <!-- Loading Indicator -->
                            @if ($isSyncing)
                                <div class="flex items-center justify-center py-4">
                                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600"></div>
                                    <span class="ml-3 text-gray-600">Menyinkronkan data...</span>
                                </div>
                            @endif
                        </div>
                        <div
                            class="border-t border-gray-200 bg-gray-50 px-6 py-4 flex justify-end gap-3 rounded-b-2xl">
                            <button type="button" wire:click="closeModal" class="btn btn-secondary"
                                {{ $isSyncing ? 'disabled' : '' }}>
                                Tutup
                            </button>
                            <button type="button" wire:click="syncFromApi" class="btn btn-primary"
                                {{ $isSyncing || empty($selectedApiSource) ? 'disabled' : '' }}>
                                @if ($isSyncing)
                                    <span
                                        class="animate-spin inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full mr-2"></span>
                                    Menyinkronkan...
                                @else
                                    Mulai Sinkronisasi
                                @endif
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    @endif
</div>
