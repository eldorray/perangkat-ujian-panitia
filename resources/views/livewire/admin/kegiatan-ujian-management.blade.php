<div>
    <x-slot name="header">Kegiatan Ujian</x-slot>

    @if (session('success'))
        <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-800">{{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800">{{ session('error') }}
        </div>
    @endif

    <div class="card">
        <div class="p-6 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="relative">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari kegiatan ujian..."
                        class="input pl-10 w-full sm:w-80">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <button wire:click="create" class="btn btn-primary">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Buat Kegiatan Ujian
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Ujian</th>
                        <th>Tahun Ajaran</th>
                        <th>No. SK</th>
                        <th>Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kegiatanUjians as $index => $kegiatan)
                        <tr wire:key="kegiatan-{{ $kegiatan->id }}">
                            <td>{{ $kegiatanUjians->firstItem() + $index }}</td>
                            <td class="font-medium">
                                {{ $kegiatan->nama_ujian }}
                            </td>
                            <td>
                                <span class="badge badge-secondary">
                                    {{ $kegiatan->tahunAjaran->nama }} - {{ $kegiatan->tahunAjaran->semester }}
                                </span>
                            </td>
                            <td>{{ $kegiatan->sk_number ?? '-' }}</td>
                            <td>
                                @if ($kegiatan->is_locked)
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                            </path>
                                        </svg>
                                        Terkunci
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        Aktif
                                    </span>
                                @endif
                            </td>
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.kegiatan-ujian.perangkat', $kegiatan->id) }}"
                                        class="btn btn-primary btn-sm" wire:navigate>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                        Lihat Perangkat
                                    </a>

                                    @if ($kegiatan->is_locked)
                                        {{-- Unlock button --}}
                                        <button wire:click="openUnlockModal({{ $kegiatan->id }})"
                                            class="btn btn-ghost btn-sm text-amber-600" title="Buka Kunci">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                        </button>
                                        {{-- Disabled edit button --}}
                                        <button class="btn btn-ghost btn-sm text-gray-400 cursor-not-allowed" disabled
                                            title="Buka kunci untuk mengedit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </button>
                                        {{-- Disabled delete button --}}
                                        <button class="btn btn-ghost btn-sm text-gray-400 cursor-not-allowed" disabled
                                            title="Buka kunci untuk menghapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    @else
                                        {{-- Lock button --}}
                                        <button wire:click="openLockModal({{ $kegiatan->id }})"
                                            class="btn btn-ghost btn-sm text-amber-600" title="Kunci Kegiatan">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                                </path>
                                            </svg>
                                        </button>
                                        {{-- Edit button --}}
                                        <button wire:click="edit({{ $kegiatan->id }})" class="btn btn-ghost btn-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </button>
                                        {{-- Delete button --}}
                                        <button wire:click="confirmDelete({{ $kegiatan->id }})"
                                            class="btn btn-ghost btn-sm text-red-600">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-gray-500">
                                Belum ada kegiatan ujian. Klik tombol "Buat Kegiatan Ujian" untuk menambahkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($kegiatanUjians->hasPages())
            <div class="p-6 border-t">{{ $kegiatanUjians->links() }}</div>
        @endif
    </div>

    <!-- Create/Edit Modal -->
    @if ($showModal)
        <template x-teleport="#modal-portal">
            <div class="fixed inset-0 z-[9999] overflow-y-auto">
                <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" wire:click="closeModal"></div>
                <div class="fixed inset-0 flex items-center justify-center p-4">
                    <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl">
                        <form wire:submit="save">
                            <div class="border-b border-gray-200 px-6 py-4">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $editingId ? 'Edit' : 'Buat' }}
                                    Kegiatan Ujian</h3>
                            </div>
                            <div class="px-6 py-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium mb-1">Nama Ujian <span
                                            class="text-red-500">*</span></label>
                                    <input type="text" wire:model="nama_ujian" class="input w-full"
                                        placeholder="Contoh: PAS Semester Ganjil 2024/2025">
                                    @error('nama_ujian')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Tahun Ajaran <span
                                            class="text-red-500">*</span></label>
                                    <select wire:model="tahun_ajaran_id" class="input w-full">
                                        <option value="">-- Pilih Tahun Ajaran --</option>
                                        @foreach ($tahunAjarans as $ta)
                                            <option value="{{ $ta->id }}">{{ $ta->nama }} -
                                                {{ $ta->semester }}</option>
                                        @endforeach
                                    </select>
                                    @error('tahun_ajaran_id')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Nomor SK</label>
                                    <input type="text" wire:model="sk_number" class="input w-full"
                                        placeholder="Contoh: 421.3/001/SK/2024">
                                    @error('sk_number')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Keterangan</label>
                                    <textarea wire:model="keterangan" class="input w-full" rows="2" placeholder="Keterangan tambahan (opsional)"></textarea>
                                    @error('keterangan')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <hr class="border-gray-200">
                                <h4 class="text-sm font-semibold text-gray-700">Panitia Ujian</h4>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium mb-1">Nama Ketua Panitia</label>
                                        <input type="text" wire:model="ketua_panitia" class="input w-full"
                                            placeholder="Nama lengkap ketua panitia">
                                        @error('ketua_panitia')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium mb-1">NIP Ketua Panitia</label>
                                        <input type="text" wire:model="nip_ketua_panitia" class="input w-full"
                                            placeholder="NIP ketua panitia">
                                        @error('nip_ketua_panitia')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium mb-1">Tanggal Dokumen</label>
                                    <input type="date" wire:model="tanggal_dokumen" class="input w-full">
                                    <p class="text-xs text-gray-400 mt-1">Tanggal yang tercetak di dokumen (kartu
                                        peserta, jadwal ujian, dll). Kosongkan untuk menggunakan tanggal hari ini.</p>
                                    @error('tanggal_dokumen')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
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

    <!-- Delete Modal -->
    @if ($showDeleteModal)
        <template x-teleport="#modal-portal">
            <div class="fixed inset-0 z-[9999] overflow-y-auto">
                <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" wire:click="closeModal"></div>
                <div class="fixed inset-0 flex items-center justify-center p-4">
                    <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl p-6">
                        <div class="flex items-start gap-4 mb-4">
                            <div
                                class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Hapus Kegiatan Ujian</h3>
                                <p class="text-sm text-gray-500 mt-1">Yakin ingin menghapus kegiatan ujian ini? Semua
                                    dokumen perangkat yang terkait juga akan dihapus.</p>
                            </div>
                        </div>
                        <div class="flex justify-end gap-3">
                            <button wire:click="closeModal" class="btn btn-secondary">Batal</button>
                            <button wire:click="delete" class="btn btn-destructive">Hapus</button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    @endif

    <!-- Lock Modal -->
    @if ($showLockModal)
        <template x-teleport="#modal-portal">
            <div class="fixed inset-0 z-[9999] overflow-y-auto" x-data="{ pin: @entangle('pin'), confirmPin: @entangle('confirmPin') }">
                <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" wire:click="closeLockModal"></div>
                <div class="fixed inset-0 flex items-center justify-center p-4">
                    <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl p-6">
                        <div class="flex items-start gap-4 mb-6">
                            <div
                                class="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Kunci Kegiatan Ujian</h3>
                                <p class="text-sm text-gray-500 mt-1">Masukkan PIN 6 digit untuk mengunci kegiatan ini.
                                    Simpan PIN dengan aman untuk membuka kunci nanti.</p>
                            </div>
                        </div>

                        <form wire:submit="lock">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium mb-2">PIN (6 digit angka)</label>
                                    <div class="flex gap-2 justify-center">
                                        @for ($i = 0; $i < 6; $i++)
                                            <input type="text" maxlength="1"
                                                class="w-12 h-14 text-center text-2xl font-bold border-2 border-gray-300 rounded-lg focus:border-amber-500 focus:ring-amber-500"
                                                x-on:input="
                                                    $event.target.value = $event.target.value.replace(/[^0-9]/g, '');
                                                    let allInputs = $el.parentElement.querySelectorAll('input');
                                                    let values = Array.from(allInputs).map(i => i.value).join('');
                                                    pin = values;
                                                    if ($event.target.value && {{ $i }} < 5) {
                                                        allInputs[{{ $i + 1 }}].focus();
                                                    }
                                                "
                                                x-on:keydown.backspace="
                                                    if (!$event.target.value && {{ $i }} > 0) {
                                                        let allInputs = $el.parentElement.querySelectorAll('input');
                                                        allInputs[{{ $i - 1 }}].focus();
                                                    }
                                                ">
                                        @endfor
                                    </div>
                                    @error('pin')
                                        <span
                                            class="text-red-500 text-sm mt-1 block text-center">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium mb-2">Konfirmasi PIN</label>
                                    <div class="flex gap-2 justify-center">
                                        @for ($i = 0; $i < 6; $i++)
                                            <input type="text" maxlength="1"
                                                class="w-12 h-14 text-center text-2xl font-bold border-2 border-gray-300 rounded-lg focus:border-amber-500 focus:ring-amber-500"
                                                x-on:input="
                                                    $event.target.value = $event.target.value.replace(/[^0-9]/g, '');
                                                    let allInputs = $el.parentElement.querySelectorAll('input');
                                                    let values = Array.from(allInputs).map(i => i.value).join('');
                                                    confirmPin = values;
                                                    if ($event.target.value && {{ $i }} < 5) {
                                                        allInputs[{{ $i + 1 }}].focus();
                                                    }
                                                "
                                                x-on:keydown.backspace="
                                                    if (!$event.target.value && {{ $i }} > 0) {
                                                        let allInputs = $el.parentElement.querySelectorAll('input');
                                                        allInputs[{{ $i - 1 }}].focus();
                                                    }
                                                ">
                                        @endfor
                                    </div>
                                    @error('confirmPin')
                                        <span
                                            class="text-red-500 text-sm mt-1 block text-center">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="flex justify-end gap-3 mt-6">
                                <button type="button" wire:click="closeLockModal"
                                    class="btn btn-secondary">Batal</button>
                                <button type="submit" class="btn btn-primary bg-amber-600 hover:bg-amber-700">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                        </path>
                                    </svg>
                                    Kunci
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </template>
    @endif

    <!-- Unlock Modal -->
    @if ($showUnlockModal)
        <template x-teleport="#modal-portal">
            <div class="fixed inset-0 z-[9999] overflow-y-auto" x-data="{ unlockPin: @entangle('unlockPin') }">
                <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" wire:click="closeLockModal"></div>
                <div class="fixed inset-0 flex items-center justify-center p-4">
                    <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl p-6">
                        <div class="flex items-start gap-4 mb-6">
                            <div
                                class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Buka Kunci Kegiatan</h3>
                                <p class="text-sm text-gray-500 mt-1">Masukkan PIN 6 digit untuk membuka kegiatan ini.
                                </p>
                            </div>
                        </div>

                        <form wire:submit="unlock">
                            <div>
                                <label class="block text-sm font-medium mb-2">PIN (6 digit angka)</label>
                                <div class="flex gap-2 justify-center">
                                    @for ($i = 0; $i < 6; $i++)
                                        <input type="text" maxlength="1"
                                            class="w-12 h-14 text-center text-2xl font-bold border-2 border-gray-300 rounded-lg focus:border-green-500 focus:ring-green-500"
                                            x-on:input="
                                                $event.target.value = $event.target.value.replace(/[^0-9]/g, '');
                                                let allInputs = $el.parentElement.querySelectorAll('input');
                                                let values = Array.from(allInputs).map(i => i.value).join('');
                                                unlockPin = values;
                                                if ($event.target.value && {{ $i }} < 5) {
                                                    allInputs[{{ $i + 1 }}].focus();
                                                }
                                            "
                                            x-on:keydown.backspace="
                                                if (!$event.target.value && {{ $i }} > 0) {
                                                    let allInputs = $el.parentElement.querySelectorAll('input');
                                                    allInputs[{{ $i - 1 }}].focus();
                                                }
                                            ">
                                    @endfor
                                </div>
                                @error('unlockPin')
                                    <span class="text-red-500 text-sm mt-2 block text-center">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="flex justify-end gap-3 mt-6">
                                <button type="button" wire:click="closeLockModal"
                                    class="btn btn-secondary">Batal</button>
                                <button type="submit" class="btn btn-primary bg-green-600 hover:bg-green-700">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    Buka Kunci
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </template>
    @endif
</div>
