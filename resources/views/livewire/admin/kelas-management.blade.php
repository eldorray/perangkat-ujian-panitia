<div>
    <x-slot name="header">Manajemen Kelas</x-slot>

    @if (session('success'))
        <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-800">{{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="p-6 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="relative">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari kelas..."
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
                    Tambah Kelas
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tingkat</th>
                        <th>Nama Kelas</th>
                        <th>Jumlah Siswa</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kelasList as $index => $kelas)
                        <tr wire:key="kelas-{{ $kelas->id }}">
                            <td>{{ $kelasList->firstItem() + $index }}</td>
                            <td><span class="badge badge-secondary">{{ $kelas->tingkat }}</span></td>
                            <td class="font-medium">{{ $kelas->nama }}</td>
                            <td>{{ $kelas->siswas_count }} siswa</td>
                            <td class="text-right">
                                <button wire:click="edit({{ $kelas->id }})"
                                    class="btn btn-ghost btn-sm">Edit</button>
                                <button wire:click="confirmDelete({{ $kelas->id }})"
                                    class="btn btn-ghost btn-sm text-red-600">Hapus</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-8 text-gray-500">Tidak ada data.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($kelasList->hasPages())
            <div class="p-6 border-t">{{ $kelasList->links() }}</div>
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
                                    Kelas</h3>
                            </div>
                            <div class="px-6 py-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium mb-1">Tingkat</label>
                                    <input type="text" wire:model="tingkat" class="input w-full"
                                        placeholder="Contoh: X, XI, XII">
                                    @error('tingkat')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Nama Kelas</label>
                                    <input type="text" wire:model="nama" class="input w-full"
                                        placeholder="Contoh: IPA-1, IPS-2">
                                    @error('nama')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div
                                class="border-t border-gray-200 bg-gray-50 px-6 py-4 flex justify-end gap-3 rounded-b-2xl">
                                <button type="button" wire:click="closeModal" class="btn btn-secondary">Batal</button>
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
                        <h3 class="text-lg font-semibold mb-2">Hapus Kelas</h3>
                        <p class="text-gray-500 mb-4">Yakin ingin menghapus kelas ini?</p>
                        <div class="flex justify-end gap-3">
                            <button wire:click="closeModal" class="btn btn-secondary">Batal</button>
                            <button wire:click="delete" class="btn btn-destructive">Hapus</button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    @endif
</div>
