<div>
    <x-slot name="header">Jadwal Ujian</x-slot>

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
            <span class="font-medium text-gray-900">Jadwal Ujian</span>
        </nav>
    </div>

    <!-- Header Info -->
    <div class="mb-6 p-4 rounded-xl bg-gray-50 border border-gray-200">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">{{ $kegiatanUjian->nama_ujian }}</h2>
                <p class="text-sm text-gray-500 mt-1">{{ $kegiatanUjian->tahunAjaran->nama }} -
                    {{ $kegiatanUjian->tahunAjaran->semester }}</p>
            </div>
            <a href="{{ route('admin.kegiatan-ujian.jadwal.print', $kegiatanUjian->id) }}" target="_blank"
                class="btn btn-secondary">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                    </path>
                </svg>
                Cetak Jadwal
            </a>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session('message'))
        <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-800">
            {{ session('message') }}
        </div>
    @endif

    <!-- Jadwal Card -->
    <div class="card">
        <div class="p-6 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h3 class="text-lg font-semibold text-gray-900">Daftar Jadwal</h3>
                <div class="flex items-center gap-3">
                    <!-- Filter Kelompok Kelas -->
                    <select wire:model.live="filterKelompok" class="input text-sm">
                        <option value="">Semua Kelompok</option>
                        @foreach ($kelompokOptions as $tingkat)
                            <option value="Kelas {{ $tingkat }}">Kelas {{ $tingkat }}</option>
                        @endforeach
                    </select>
                    <button wire:click="create" class="btn btn-primary">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                            </path>
                        </svg>
                        Tambah Jadwal
                    </button>
                </div>
            </div>
        </div>

        <div class="p-6">
            @forelse($jadwalGrouped as $kelompok => $jadwalByDate)
                <div class="mb-8 last:mb-0">
                    <!-- Kelompok Kelas Header -->
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">{{ $kelompok }}</h3>
                        </div>
                    </div>

                    @foreach ($jadwalByDate as $tanggal => $jadwals)
                        <div class="mb-6 last:mb-0 ml-4">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ $jadwals->first()->hari_tanggal }}</h4>
                                </div>
                            </div>

                            <div class="overflow-x-auto rounded-xl border border-gray-200">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="w-16">No</th>
                                            <th class="w-36">Waktu</th>
                                            <th>Mata Pelajaran</th>
                                            <th>Keterangan</th>
                                            <th class="text-right w-44">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($jadwals as $index => $jadwal)
                                            <tr>
                                                <td class="text-center">{{ $index + 1 }}</td>
                                                <td class="font-mono">{{ $jadwal->waktu }}</td>
                                                <td class="font-medium">{{ $jadwal->mata_pelajaran }}</td>
                                                <td class="text-gray-500">{{ $jadwal->keterangan ?? '-' }}</td>
                                                <td class="text-right">
                                                    <button wire:click="duplicate({{ $jadwal->id }})"
                                                        class="btn btn-ghost btn-sm"
                                                        title="Duplikat untuk kelompok lain">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z">
                                                            </path>
                                                        </svg>
                                                    </button>
                                                    <button wire:click="edit({{ $jadwal->id }})"
                                                        class="btn btn-ghost btn-sm">Edit</button>
                                                    <button wire:click="delete({{ $jadwal->id }})"
                                                        wire:confirm="Yakin ingin menghapus jadwal ini?"
                                                        class="btn btn-ghost btn-sm text-red-600">Hapus</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach
                </div>
            @empty
                <div class="text-center py-12 text-gray-500">
                    <svg class="mx-auto h-12 w-12 text-gray-300 mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    <p>Belum ada jadwal ujian</p>
                    <p class="text-sm mt-1">Klik tombol "Tambah Jadwal" untuk memulai</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Form Modal -->
    @if ($showFormModal)
        <template x-teleport="#modal-portal">
            <div class="fixed inset-0 z-[9999] overflow-y-auto">
                <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" wire:click="closeModal"></div>
                <div class="fixed inset-0 flex items-center justify-center p-4">
                    <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl">
                        <form wire:submit="save">
                            <div class="border-b border-gray-200 px-6 py-4">
                                <h3 class="text-lg font-semibold text-gray-900">
                                    {{ $editingId ? 'Edit' : 'Tambah' }} Jadwal
                                </h3>
                            </div>
                            <div class="px-6 py-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium mb-1">Kelompok Kelas</label>
                                    <select wire:model="kelompok_kelas" class="input w-full">
                                        <option value="">Semua Kelas</option>
                                        @foreach ($kelompokOptions as $tingkat)
                                            <option value="Kelas {{ $tingkat }}">Kelas {{ $tingkat }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="text-xs text-gray-500 mt-1">Pilih kelompok kelas yang mengikuti jadwal
                                        ini. Kosongkan jika berlaku untuk semua kelas.</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium mb-1">Tanggal</label>
                                    <input type="date" wire:model="tanggal" class="input w-full">
                                    @error('tanggal')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium mb-1">Jam Mulai</label>
                                        <input type="time" wire:model="jam_mulai" class="input w-full">
                                        @error('jam_mulai')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium mb-1">Jam Selesai</label>
                                        <input type="time" wire:model="jam_selesai" class="input w-full">
                                        @error('jam_selesai')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium mb-1">Mata Pelajaran</label>
                                    <input type="text" wire:model="mata_pelajaran" class="input w-full"
                                        placeholder="Contoh: Matematika">
                                    @error('mata_pelajaran')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium mb-1">Keterangan (Opsional)</label>
                                    <input type="text" wire:model="keterangan" class="input w-full"
                                        placeholder="Keterangan tambahan">
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
</div>
