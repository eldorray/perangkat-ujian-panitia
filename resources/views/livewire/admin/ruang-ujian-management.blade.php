<div>
    <x-slot name="header">Manajemen Ruang Ujian</x-slot>

    @if (session('success'))
        <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-800">{{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="p-6 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="relative">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari ruang ujian..."
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
                    Tambah Ruang
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama Ruangan</th>
                        <th>Kapasitas</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ruangUjians as $index => $ruang)
                        <tr wire:key="ruang-{{ $ruang->id }}">
                            <td>{{ $ruangUjians->firstItem() + $index }}</td>
                            <td class="font-mono">{{ $ruang->kode }}</td>
                            <td class="font-medium">{{ $ruang->nama }}</td>
                            <td>{{ $ruang->kapasitas }} orang</td>
                            <td class="text-right">
                                <button wire:click="edit({{ $ruang->id }})"
                                    class="btn btn-ghost btn-sm">Edit</button>
                                <button wire:click="confirmDelete({{ $ruang->id }})"
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
        @if ($ruangUjians->hasPages())
            <div class="p-6 border-t">{{ $ruangUjians->links() }}</div>
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
                                    Ruang Ujian</h3>
                            </div>
                            <div class="px-6 py-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium mb-1">Kode Ruangan</label>
                                    <input type="text" wire:model="kode" class="input w-full"
                                        placeholder="Contoh: R01, LAB-1">
                                    @error('kode')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Nama Ruangan</label>
                                    <input type="text" wire:model="nama" class="input w-full"
                                        placeholder="Contoh: Ruang 101">
                                    @error('nama')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Kapasitas</label>
                                    <input type="number" wire:model="kapasitas" class="input w-full"
                                        placeholder="Jumlah peserta maksimal" min="1">
                                    @error('kapasitas')
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
                        <h3 class="text-lg font-semibold mb-2">Hapus Ruang Ujian</h3>
                        <p class="text-gray-500 mb-4">Yakin ingin menghapus ruang ujian ini?</p>
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
