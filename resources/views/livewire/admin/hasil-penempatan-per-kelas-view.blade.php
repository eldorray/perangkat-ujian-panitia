<div>
    <x-slot name="header">Hasil Penempatan Per Kelas</x-slot>

    <!-- Breadcrumb -->
    <div class="mb-6">
        <nav class="flex items-center gap-2 text-sm text-gray-500">
            <a href="{{ route('admin.kegiatan-ujian') }}" class="hover:text-gray-700" wire:navigate>Kegiatan Ujian</a>
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            <a href="{{ route('admin.kegiatan-ujian.perangkat', $kegiatanUjian->id) }}" class="hover:text-gray-700"
                wire:navigate>Perangkat</a>
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            <a href="{{ route('admin.kegiatan-ujian.penempatan-per-kelas', $kegiatanUjian->id) }}"
                class="hover:text-gray-700" wire:navigate>Penempatan Per Kelas</a>
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            <span class="font-medium text-gray-900">Hasil Penempatan</span>
        </nav>
    </div>

    <!-- Flash Messages -->
    @if (session('message'))
        <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-800">
            {{ session('message') }}
        </div>
    @endif

    <!-- Header Info -->
    <div class="mb-6 p-4 rounded-xl bg-gray-50 border border-gray-200">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">{{ $kegiatanUjian->nama_ujian }}</h2>
                <p class="text-sm text-gray-500 mt-1">
                    Kelas: <span class="font-medium">{{ $kelasNama }}</span>
                </p>
            </div>
            <div class="flex items-center gap-3">
                <span class="badge badge-primary">{{ $totalSiswa }} siswa ditempatkan</span>
                <a href="{{ route('admin.kegiatan-ujian.penempatan-per-kelas', $kegiatanUjian->id) }}"
                    class="btn btn-secondary" wire:navigate>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Penempatan per Ruang -->
    @forelse ($penempatanByRuang as $ruangId => $penempatans)
        @php
            $ruang = $penempatans->first()->ruangUjian;
        @endphp
        <div class="card mb-6">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">{{ $ruang->kode }} - {{ $ruang->nama }}</h3>
                            <p class="text-sm text-gray-500">Kapasitas: {{ $ruang->kapasitas }} siswa</p>
                        </div>
                    </div>
                    <span class="badge badge-secondary">{{ $penempatans->count() }} / {{ $ruang->kapasitas }}
                        terisi</span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="w-16">No</th>
                            <th>No. Peserta</th>
                            <th>Nama Lengkap</th>
                            <th>Jenis Kelamin</th>
                            <th class="w-24 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($penempatans as $penempatan)
                            <tr>
                                <td class="text-center">{{ $penempatan->nomor_urut }}</td>
                                <td class="font-mono">{{ $penempatan->nomor_peserta ?? '-' }}</td>
                                <td class="font-medium">{{ $penempatan->siswa->nama_lengkap }}</td>
                                <td>
                                    @if ($penempatan->siswa->jenis_kelamin === 'L')
                                        <span class="badge badge-primary">Laki-laki</span>
                                    @else
                                        <span class="badge badge-secondary">Perempuan</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <button wire:click="openEditModal({{ $penempatan->id }})"
                                            class="btn btn-ghost btn-sm text-blue-600" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </button>
                                        <button wire:click="confirmDelete({{ $penempatan->id }})"
                                            class="btn btn-ghost btn-sm text-red-600" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @empty
        <div class="card">
            <div class="p-12 text-center text-gray-500">
                <svg class="mx-auto h-12 w-12 text-gray-300 mb-4" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                <p>Belum ada data penempatan</p>
            </div>
        </div>
    @endforelse

    <!-- Edit Modal -->
    @if ($showEditModal)
        <template x-teleport="#modal-portal">
            <div class="fixed inset-0 z-[9999] overflow-y-auto">
                <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" wire:click="closeEditModal"></div>
                <div class="fixed inset-0 flex items-center justify-center p-4">
                    <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl">
                        <form wire:submit="updatePenempatan">
                            <div class="border-b border-gray-200 px-6 py-4">
                                <h3 class="text-lg font-semibold text-gray-900">Edit Penempatan Siswa</h3>
                            </div>
                            <div class="px-6 py-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium mb-1">Ruang Ujian</label>
                                    <select wire:model="selectedRuangId" class="input w-full">
                                        <option value="">Pilih Ruang</option>
                                        @foreach ($ruangList as $ruang)
                                            <option value="{{ $ruang->id }}">{{ $ruang->kode }} -
                                                {{ $ruang->nama }}
                                                ({{ $ruang->kapasitas }} siswa)
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('selectedRuangId')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Nomor Urut</label>
                                    <input type="number" wire:model="selectedNomorUrut" class="input w-full"
                                        min="1">
                                    @error('selectedNomorUrut')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div
                                class="border-t border-gray-200 bg-gray-50 px-6 py-4 flex justify-end gap-3 rounded-b-2xl">
                                <button type="button" wire:click="closeEditModal"
                                    class="btn btn-secondary">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </template>
    @endif

    <!-- Delete Confirmation Modal -->
    @if ($showDeleteModal)
        <template x-teleport="#modal-portal">
            <div class="fixed inset-0 z-[9999] overflow-y-auto">
                <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" wire:click="$set('showDeleteModal', false)">
                </div>
                <div class="fixed inset-0 flex items-center justify-center p-4">
                    <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl p-6">
                        <h3 class="text-lg font-semibold mb-2">Hapus Penempatan?</h3>
                        <p class="text-gray-500 mb-4">Siswa ini akan dihapus dari penempatan ruang ujian.</p>
                        <div class="flex justify-end gap-3">
                            <button wire:click="$set('showDeleteModal', false)"
                                class="btn btn-secondary">Batal</button>
                            <button wire:click="deletePenempatan" class="btn btn-destructive">Hapus</button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    @endif
</div>
