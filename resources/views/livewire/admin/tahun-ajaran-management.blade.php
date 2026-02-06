<div>
    <x-slot name="header">Manajemen Tahun Ajaran</x-slot>

    @if (session('success'))
        <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-800">{{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="p-6 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="relative">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari tahun ajaran..."
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
                    Tambah Tahun Ajaran
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Semester</th>
                        <th>Periode</th>
                        <th>Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tahunAjarans as $index => $ta)
                        <tr wire:key="ta-{{ $ta->id }}">
                            <td>{{ $tahunAjarans->firstItem() + $index }}</td>
                            <td class="font-medium">{{ $ta->nama }}</td>
                            <td>{{ $ta->semester }}</td>
                            <td>
                                @if ($ta->tanggal_mulai && $ta->tanggal_selesai)
                                    {{ $ta->tanggal_mulai->format('d/m/Y') }} -
                                    {{ $ta->tanggal_selesai->format('d/m/Y') }}
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td>
                                @if ($ta->is_active)
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-secondary">Tidak Aktif</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <button wire:click="edit({{ $ta->id }})"
                                    class="btn btn-ghost btn-sm">Edit</button>
                                <button wire:click="confirmDelete({{ $ta->id }})"
                                    class="btn btn-ghost btn-sm text-red-600">Hapus</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-gray-500">Tidak ada data.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($tahunAjarans->hasPages())
            <div class="p-6 border-t">{{ $tahunAjarans->links() }}</div>
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
                                    Tahun Ajaran</h3>
                            </div>
                            <div class="px-6 py-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium mb-1">Nama Tahun Ajaran</label>
                                    <input type="text" wire:model="nama" class="input w-full"
                                        placeholder="Contoh: 2024/2025">
                                    @error('nama')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Semester</label>
                                    <select wire:model="semester" class="input w-full">
                                        <option value="Ganjil">Ganjil</option>
                                        <option value="Genap">Genap</option>
                                    </select>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium mb-1">Tanggal Mulai</label>
                                        <input type="date" wire:model="tanggal_mulai" class="input w-full">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium mb-1">Tanggal Selesai</label>
                                        <input type="date" wire:model="tanggal_selesai" class="input w-full">
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <input type="checkbox" wire:model="is_active" id="is_active"
                                        class="rounded border-gray-300">
                                    <label for="is_active" class="text-sm font-medium">Aktifkan tahun ajaran ini</label>
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
                        <h3 class="text-lg font-semibold mb-2">Hapus Tahun Ajaran</h3>
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
</div>
