<div>
    <x-slot name="header">Surat Tugas</x-slot>

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
            <span class="font-medium text-gray-900">Surat Tugas</span>
        </nav>
    </div>

    <!-- Header Info -->
    <div class="mb-6 p-4 rounded-xl bg-gray-50 border border-gray-200">
        <h2 class="text-lg font-semibold text-gray-900">{{ $kegiatanUjian->nama_ujian }}</h2>
        <p class="text-sm text-gray-500 mt-1">{{ $kegiatanUjian->tahunAjaran->nama }} -
            {{ $kegiatanUjian->tahunAjaran->semester }}</p>
    </div>

    <!-- Flash Messages -->
    @if (session('message'))
        <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-800">
            {{ session('message') }}
        </div>
    @endif

    <!-- Surat Tugas Card -->
    <div class="card">
        <div class="p-6 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h3 class="text-lg font-semibold text-gray-900">Daftar Surat Tugas</h3>
                <button wire:click="create" class="btn btn-primary">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                        </path>
                    </svg>
                    Buat Surat Tugas
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nomor Surat</th>
                        <th>Tanggal</th>
                        <th>Keperluan</th>
                        <th>Jumlah Guru</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suratTugasList as $index => $suratTugas)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="font-mono">{{ $suratTugas->nomor_surat }}</td>
                            <td>{{ $suratTugas->tanggal_surat->translatedFormat('d F Y') }}</td>
                            <td>{{ Str::limit($suratTugas->untuk_keperluan, 40) }}</td>
                            <td>{{ $suratTugas->gurus_count }} guru</td>
                            <td class="text-right">
                                <a href="{{ route('admin.kegiatan-ujian.surat-tugas.print', ['id' => $kegiatanUjian->id, 'suratTugasId' => $suratTugas->id]) }}"
                                    target="_blank" class="btn btn-ghost btn-sm">Cetak</a>
                                <button wire:click="edit({{ $suratTugas->id }})"
                                    class="btn btn-ghost btn-sm">Edit</button>
                                <button wire:click="delete({{ $suratTugas->id }})"
                                    wire:confirm="Yakin ingin menghapus surat tugas ini?"
                                    class="btn btn-ghost btn-sm text-red-600">Hapus</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-gray-500">Belum ada surat tugas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Form Modal -->
    @if ($showFormModal)
        <template x-teleport="#modal-portal">
            <div class="fixed inset-0 z-[9999] overflow-y-auto">
                <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" wire:click="closeModal"></div>
                <div class="fixed inset-0 flex items-center justify-center p-4">
                    <div class="relative w-full max-w-2xl max-h-[90vh] overflow-y-auto bg-white rounded-2xl shadow-2xl">
                        <form wire:submit="save">
                            <div class="border-b border-gray-200 px-6 py-4 sticky top-0 bg-white">
                                <h3 class="text-lg font-semibold text-gray-900">
                                    {{ $editingId ? 'Edit' : 'Buat' }} Surat Tugas
                                </h3>
                            </div>
                            <div class="px-6 py-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium mb-1">Nomor Surat</label>
                                    <input type="text" wire:model="nomor_surat" class="input w-full"
                                        placeholder="Contoh: 001/ST/I/2026">
                                    @error('nomor_surat')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium mb-1">Tanggal Surat</label>
                                    <input type="date" wire:model="tanggal_surat" class="input w-full">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium mb-1">Dasar Surat (Opsional)</label>
                                    <textarea wire:model="dasar_surat" class="input w-full" rows="2"
                                        placeholder="Contoh: 1. Kalender Pendidikan Tahun Pelajaran 2025/2026"></textarea>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium mb-1">Untuk Keperluan</label>
                                    <textarea wire:model="untuk_keperluan" class="input w-full" rows="2"
                                        placeholder="Contoh: Pelaksanaan Ujian Akhir Semester Ganjil"></textarea>
                                    @error('untuk_keperluan')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium mb-1">Tanggal Mulai</label>
                                        <input type="date" wire:model="tanggal_mulai" class="input w-full">
                                        @error('tanggal_mulai')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium mb-1">Tanggal Selesai</label>
                                        <input type="date" wire:model="tanggal_selesai" class="input w-full">
                                        @error('tanggal_selesai')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium mb-2">Pilih Guru</label>
                                    @error('selectedGurus')
                                        <span class="text-red-500 text-sm block mb-2">{{ $message }}</span>
                                    @enderror
                                    <div class="border border-gray-200 rounded-xl max-h-60 overflow-y-auto">
                                        @foreach ($guruList as $guru)
                                            <label
                                                class="flex items-center gap-3 p-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-b-0">
                                                <input type="checkbox" value="{{ $guru->id }}"
                                                    wire:model="selectedGurus"
                                                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                                <div>
                                                    <div class="font-medium">{{ $guru->full_name_with_titles }}</div>
                                                    <div class="text-sm text-gray-500">NIP: {{ $guru->nip ?? '-' }}
                                                    </div>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                    <p class="text-sm text-gray-500 mt-2">{{ count($selectedGurus) }} guru dipilih</p>
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
</div>
