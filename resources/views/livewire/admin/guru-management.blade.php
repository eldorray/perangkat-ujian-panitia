<div>
    <x-slot name="header">Manajemen Guru</x-slot>

    @if (session('success'))
        <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-800">{{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="p-6 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex gap-4 items-center">
                    <div class="relative">
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari guru..."
                            class="input pl-10 w-full sm:w-80">
                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <select wire:model.live="statusFilter" class="input w-32">
                        <option value="">Semua</option>
                        <option value="1">Aktif</option>
                        <option value="0">Tidak Aktif</option>
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
                        Tambah Guru
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
                        <th>NIK</th>
                        <th>NIP/NUPTK</th>
                        <th>L/P</th>
                        <th>Status Pegawai</th>
                        <th>Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($gurus as $index => $guru)
                        <tr wire:key="guru-{{ $guru->id }}">
                            <td>{{ $gurus->firstItem() + $index }}</td>
                            <td class="font-medium">{{ $guru->full_name_with_titles }}</td>
                            <td class="font-mono text-sm">{{ $guru->nik }}</td>
                            <td class="font-mono text-sm">{{ $guru->nip ?? ($guru->nuptk ?? '-') }}</td>
                            <td>{{ $guru->gender_label }}</td>
                            <td>
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $guru->status_pegawai === 'PNS' ? 'bg-blue-100 text-blue-800' : ($guru->status_pegawai === 'GTY' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ $guru->status_pegawai }}
                                </span>
                            </td>
                            <td>
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $guru->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $guru->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                            </td>
                            <td class="text-right">
                                <button wire:click="viewDetail({{ $guru->id }})"
                                    class="btn btn-ghost btn-sm">Detail</button>
                                <button wire:click="edit({{ $guru->id }})"
                                    class="btn btn-ghost btn-sm">Edit</button>
                                <button wire:click="confirmDelete({{ $guru->id }})"
                                    class="btn btn-ghost btn-sm text-red-600">Hapus</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-8 text-gray-500">Tidak ada data guru.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($gurus->hasPages())
            <div class="p-6 border-t">{{ $gurus->links() }}</div>
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
                                    Guru</h3>
                            </div>
                            <div class="px-6 py-4 space-y-6">
                                <!-- Identitas Utama -->
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-700 mb-3 pb-2 border-b">Identitas Utama
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium mb-1">NIK <span
                                                    class="text-red-500">*</span></label>
                                            <input type="text" wire:model="nik" class="input w-full"
                                                placeholder="16 digit NIK" maxlength="16">
                                            @error('nik')
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium mb-1">NIP</label>
                                            <input type="text" wire:model="nip" class="input w-full"
                                                placeholder="NIP (untuk PNS)">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium mb-1">NUPTK</label>
                                            <input type="text" wire:model="nuptk" class="input w-full"
                                                placeholder="NUPTK">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium mb-1">NPK</label>
                                            <input type="text" wire:model="npk" class="input w-full"
                                                placeholder="NPK (Kemenag)">
                                        </div>
                                    </div>
                                </div>

                                <!-- Nama & Gelar -->
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-700 mb-3 pb-2 border-b">Nama & Gelar</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium mb-1">Gelar Depan</label>
                                            <input type="text" wire:model="front_title" class="input w-full"
                                                placeholder="Dr., H., Drs.">
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="block text-sm font-medium mb-1">Nama Lengkap <span
                                                    class="text-red-500">*</span></label>
                                            <input type="text" wire:model="full_name" class="input w-full"
                                                placeholder="Nama lengkap tanpa gelar">
                                            @error('full_name')
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium mb-1">Gelar Belakang</label>
                                            <input type="text" wire:model="back_title" class="input w-full"
                                                placeholder="S.Pd., M.Pd.">
                                        </div>
                                    </div>
                                </div>

                                <!-- Biodata -->
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-700 mb-3 pb-2 border-b">Biodata</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium mb-1">Jenis Kelamin <span
                                                    class="text-red-500">*</span></label>
                                            <select wire:model="gender" class="input w-full">
                                                <option value="L">Laki-laki</option>
                                                <option value="P">Perempuan</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium mb-1">Tempat Lahir</label>
                                            <input type="text" wire:model="pob" class="input w-full"
                                                placeholder="Tempat lahir">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium mb-1">Tanggal Lahir</label>
                                            <input type="date" wire:model="dob" class="input w-full">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium mb-1">No. Telepon</label>
                                            <input type="text" wire:model="phone_number" class="input w-full"
                                                placeholder="Nomor telepon">
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="block text-sm font-medium mb-1">Alamat</label>
                                            <textarea wire:model="address" class="input w-full" rows="2" placeholder="Alamat lengkap"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Jabatan & Status -->
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-700 mb-3 pb-2 border-b">Jabatan & Status
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium mb-1">Status Pegawai</label>
                                            <select wire:model="status_pegawai" class="input w-full">
                                                <option value="GTY">GTY (Guru Tetap Yayasan)</option>
                                                <option value="GTT">GTT (Guru Tidak Tetap)</option>
                                                <option value="PNS">PNS</option>
                                            </select>
                                        </div>
                                        <div class="flex items-center pt-6">
                                            <label class="flex items-center gap-2 cursor-pointer">
                                                <input type="checkbox" wire:model="is_active"
                                                    class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                                <span class="text-sm font-medium">Guru Aktif</span>
                                            </label>
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
    @if ($showDetailModal && $viewingGuru)
        <template x-teleport="#modal-portal">
            <div class="fixed inset-0 z-[9999] overflow-y-auto">
                <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" wire:click="closeModal"></div>
                <div class="fixed inset-0 flex items-center justify-center p-4">
                    <div
                        class="relative w-full max-w-2xl bg-white rounded-2xl shadow-2xl max-h-[90vh] overflow-y-auto">
                        <div class="border-b border-gray-200 px-6 py-4 sticky top-0 bg-white rounded-t-2xl">
                            <h3 class="text-lg font-semibold text-gray-900">Detail Guru</h3>
                        </div>
                        <div class="px-6 py-4 space-y-6">
                            <!-- Identitas -->
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700 mb-3 pb-2 border-b">Identitas</h4>
                                <dl class="grid grid-cols-2 gap-x-4 gap-y-3 text-sm">
                                    <div>
                                        <dt class="text-gray-500">Nama Lengkap</dt>
                                        <dd class="font-medium">{{ $viewingGuru->full_name_with_titles }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-gray-500">NIK</dt>
                                        <dd class="font-mono">{{ $viewingGuru->nik }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-gray-500">NIP</dt>
                                        <dd class="font-mono">{{ $viewingGuru->nip ?? '-' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-gray-500">NUPTK</dt>
                                        <dd class="font-mono">{{ $viewingGuru->nuptk ?? '-' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-gray-500">NPK</dt>
                                        <dd class="font-mono">{{ $viewingGuru->npk ?? '-' }}</dd>
                                    </div>
                                </dl>
                            </div>

                            <!-- Biodata -->
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700 mb-3 pb-2 border-b">Biodata</h4>
                                <dl class="grid grid-cols-2 gap-x-4 gap-y-3 text-sm">
                                    <div>
                                        <dt class="text-gray-500">Jenis Kelamin</dt>
                                        <dd>{{ $viewingGuru->gender_label }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-gray-500">Tempat, Tanggal Lahir</dt>
                                        <dd>{{ $viewingGuru->pob ?? '-' }},
                                            {{ $viewingGuru->dob?->format('d M Y') ?? '-' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-gray-500">No. Telepon</dt>
                                        <dd>{{ $viewingGuru->phone_number ?? '-' }}</dd>
                                    </div>
                                    <div class="col-span-2">
                                        <dt class="text-gray-500">Alamat</dt>
                                        <dd>{{ $viewingGuru->address ?? '-' }}</dd>
                                    </div>
                                </dl>
                            </div>

                            <!-- Status -->
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700 mb-3 pb-2 border-b">Status</h4>
                                <dl class="grid grid-cols-2 gap-x-4 gap-y-3 text-sm">
                                    <div>
                                        <dt class="text-gray-500">Status Pegawai</dt>
                                        <dd><span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $viewingGuru->status_pegawai === 'PNS' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">{{ $viewingGuru->status_pegawai }}</span>
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-gray-500">Status</dt>
                                        <dd><span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $viewingGuru->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">{{ $viewingGuru->is_active ? 'Aktif' : 'Tidak Aktif' }}</span>
                                        </dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                        <div
                            class="border-t border-gray-200 bg-gray-50 px-6 py-4 flex justify-end gap-3 rounded-b-2xl sticky bottom-0">
                            <button type="button" wire:click="closeModal" class="btn btn-secondary">Tutup</button>
                            <button type="button" wire:click="edit({{ $viewingGuru->id }})"
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
                        <h3 class="text-lg font-semibold mb-2">Hapus Guru</h3>
                        <p class="text-gray-500 mb-4">Yakin ingin menghapus data guru ini?</p>
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
                            <h3 class="text-lg font-semibold text-gray-900">Sync Data Guru dari API</h3>
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
                                                class="bg-blue-100 px-1 rounded">{{ env('SYNC_API_BASE_URL', 'https://datainduk.ypdhalmadani.sch.id') }}/api/{{ $selectedApiSource }}/all</code>
                                        @else
                                            <code class="bg-gray-100 px-1 rounded text-gray-500">Pilih sumber data
                                                terlebih dahulu</code>
                                        @endif
                                    </li>
                                    <li>Guru yang sudah ada (berdasarkan NIK) akan diperbarui</li>
                                    <li>Guru baru akan ditambahkan ke database</li>
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
                                                <div class="text-xs text-gray-500">Guru Baru</div>
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
