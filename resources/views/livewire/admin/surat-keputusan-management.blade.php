<div>
    <x-slot name="header">Surat Keputusan</x-slot>

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
            <span class="font-medium text-gray-900">Surat Keputusan</span>
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

    <!-- Surat Keputusan Card -->
    <div class="card">
        <div class="p-6 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h3 class="text-lg font-semibold text-gray-900">Daftar Surat Keputusan</h3>
                <button wire:click="create" class="btn btn-primary">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                        </path>
                    </svg>
                    Buat Surat Keputusan
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
                        <th>Tentang</th>
                        <th>Jumlah Panitia</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suratKeputusanList as $index => $sk)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="font-mono">{{ $sk->nomor_surat }}</td>
                            <td>{{ $sk->tanggal_surat->translatedFormat('d F Y') }}</td>
                            <td>{{ Str::limit($sk->tentang, 40) }}</td>
                            <td>{{ $sk->panitia_count }} orang</td>
                            <td class="text-right">
                                <a href="{{ route('admin.kegiatan-ujian.surat-keputusan.print', ['id' => $kegiatanUjian->id, 'suratKeputusanId' => $sk->id]) }}"
                                    target="_blank" class="btn btn-ghost btn-sm">Cetak</a>
                                <button wire:click="edit({{ $sk->id }})"
                                    class="btn btn-ghost btn-sm">Edit</button>
                                <button wire:click="delete({{ $sk->id }})"
                                    wire:confirm="Yakin ingin menghapus surat keputusan ini?"
                                    class="btn btn-ghost btn-sm text-red-600">Hapus</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-gray-500">Belum ada surat keputusan.</td>
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
                    <div class="relative w-full max-w-4xl max-h-[90vh] overflow-y-auto bg-white rounded-2xl shadow-2xl">
                        <form wire:submit="save">
                            <div class="border-b border-gray-200 px-6 py-4 sticky top-0 bg-white z-10 rounded-t-2xl">
                                <h3 class="text-lg font-semibold text-gray-900">
                                    {{ $editingId ? 'Edit' : 'Buat' }} Surat Keputusan
                                </h3>
                            </div>
                            <div class="px-6 py-4 space-y-5">
                                <!-- Nomor & Tanggal -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium mb-1">Nomor Surat</label>
                                        <input type="text" wire:model="nomor_surat" class="input w-full"
                                            placeholder="Contoh: 001/SK/I/2026">
                                        @error('nomor_surat')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium mb-1">Tanggal Surat</label>
                                        <input type="date" wire:model="tanggal_surat" class="input w-full">
                                        @error('tanggal_surat')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Tentang -->
                                <div>
                                    <label class="block text-sm font-medium mb-1">Tentang</label>
                                    <input type="text" wire:model="tentang" class="input w-full"
                                        placeholder="Penetapan Panitia ...">
                                    @error('tentang')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Menimbang -->
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <label class="block text-sm font-medium">Menimbang</label>
                                        <button type="button" wire:click="addMenimbangRow"
                                            class="text-sm text-blue-600 hover:text-blue-800">+ Tambah Poin</button>
                                    </div>
                                    @error('menimbang')
                                        <span class="text-red-500 text-sm block mb-2">{{ $message }}</span>
                                    @enderror
                                    <div class="space-y-2">
                                        @foreach ($menimbang as $index => $item)
                                            <div class="flex items-start gap-2">
                                                <span
                                                    class="text-sm text-gray-500 mt-2 w-6 flex-shrink-0">{{ chr(97 + $index) }}.</span>
                                                <textarea wire:model="menimbang.{{ $index }}" class="input w-full" rows="2"
                                                    placeholder="Poin menimbang..."></textarea>
                                                @if (count($menimbang) > 1)
                                                    <button type="button"
                                                        wire:click="removeMenimbangRow({{ $index }})"
                                                        class="text-red-500 hover:text-red-700 mt-2 flex-shrink-0">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                    </button>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Mengingat -->
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <label class="block text-sm font-medium">Mengingat</label>
                                        <button type="button" wire:click="addMengingatRow"
                                            class="text-sm text-blue-600 hover:text-blue-800">+ Tambah Poin</button>
                                    </div>
                                    @error('mengingat')
                                        <span class="text-red-500 text-sm block mb-2">{{ $message }}</span>
                                    @enderror
                                    <div class="space-y-2">
                                        @foreach ($mengingat as $index => $item)
                                            <div class="flex items-start gap-2">
                                                <span
                                                    class="text-sm text-gray-500 mt-2 w-6 flex-shrink-0">{{ $index + 1 }}.</span>
                                                <textarea wire:model="mengingat.{{ $index }}" class="input w-full" rows="2"
                                                    placeholder="Poin mengingat..."></textarea>
                                                @if (count($mengingat) > 1)
                                                    <button type="button"
                                                        wire:click="removeMengingatRow({{ $index }})"
                                                        class="text-red-500 hover:text-red-700 mt-2 flex-shrink-0">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                    </button>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Memperhatikan -->
                                <div>
                                    <label class="block text-sm font-medium mb-1">Memperhatikan</label>
                                    <textarea wire:model="memperhatikan" class="input w-full" rows="2" placeholder="Hasil rapat dewan guru..."></textarea>
                                </div>

                                <!-- Ditetapkan di -->
                                <div>
                                    <label class="block text-sm font-medium mb-1">Ditetapkan di</label>
                                    <input type="text" wire:model="ditetapkan_di" class="input w-full"
                                        placeholder="Nama Kota/Kabupaten">
                                </div>

                                <!-- Susunan Panitia -->
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <label class="block text-sm font-medium">Susunan Panitia</label>
                                        <button type="button" wire:click="addPanitiaRow"
                                            class="text-sm text-blue-600 hover:text-blue-800">+ Tambah Panitia</button>
                                    </div>
                                    @error('panitiaAssignments')
                                        <span class="text-red-500 text-sm block mb-2">{{ $message }}</span>
                                    @enderror
                                    <div class="border border-gray-200 rounded-xl overflow-hidden">
                                        <table class="w-full text-sm">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th class="text-left px-4 py-2 w-8">No</th>
                                                    <th class="text-left px-4 py-2">Jabatan</th>
                                                    <th class="text-left px-4 py-2">Guru</th>
                                                    <th class="text-center px-4 py-2 w-10"></th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-100">
                                                @foreach ($panitiaAssignments as $index => $assignment)
                                                    <tr class="hover:bg-gray-50">
                                                        <td class="px-4 py-2 text-gray-500">{{ $index + 1 }}</td>
                                                        <td class="px-4 py-2">
                                                            <input type="text"
                                                                wire:model="panitiaAssignments.{{ $index }}.jabatan"
                                                                class="input w-full text-sm" placeholder="Jabatan">
                                                            @error("panitiaAssignments.{$index}.jabatan")
                                                                <span
                                                                    class="text-red-500 text-xs">{{ $message }}</span>
                                                            @enderror
                                                        </td>
                                                        <td class="px-4 py-2">
                                                            <select
                                                                wire:model="panitiaAssignments.{{ $index }}.guru_id"
                                                                class="input w-full text-sm">
                                                                <option value="">-- Pilih Guru --</option>
                                                                @foreach ($guruList as $guru)
                                                                    <option value="{{ $guru->id }}">
                                                                        {{ $guru->full_name_with_titles }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @error("panitiaAssignments.{$index}.guru_id")
                                                                <span
                                                                    class="text-red-500 text-xs">{{ $message }}</span>
                                                            @enderror
                                                        </td>
                                                        <td class="px-4 py-2 text-center">
                                                            @if (count($panitiaAssignments) > 1)
                                                                <button type="button"
                                                                    wire:click="removePanitiaRow({{ $index }})"
                                                                    class="text-red-500 hover:text-red-700">
                                                                    <svg class="w-4 h-4" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M6 18L18 6M6 6l12 12"></path>
                                                                    </svg>
                                                                </button>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
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
</div>
