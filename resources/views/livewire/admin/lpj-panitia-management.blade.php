<div>
    <x-slot name="header">LPJ Panitia</x-slot>

    @push('styles')
        <link rel="stylesheet" href="/quill/dist/quill.snow.css">
        <style>
            .quill-wrapper .ql-container {
                min-height: 100px;
                font-size: 14px;
            }

            .quill-wrapper .ql-editor {
                min-height: 100px;
            }

            .quill-wrapper.short .ql-container {
                min-height: 60px;
            }

            .quill-wrapper.short .ql-editor {
                min-height: 60px;
            }

            .quill-wrapper .ql-toolbar {
                border-radius: 0.5rem 0.5rem 0 0;
                border-color: #e5e7eb;
            }

            .quill-wrapper .ql-container {
                border-radius: 0 0 0.5rem 0.5rem;
                border-color: #e5e7eb;
            }
        </style>
    @endpush

    @push('scripts')
        <script src="/quill/dist/quill.js"></script>
    @endpush

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
            <a href="{{ route('admin.kegiatan-ujian.daftar-hadir-panitia', $kegiatanUjian->id) }}"
                class="hover:text-gray-700" wire:navigate>Menu Panitia</a>
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            <span class="font-medium text-gray-900">LPJ Panitia</span>
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

    <!-- LPJ Card -->
    <div class="card">
        <div class="p-6 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h3 class="text-lg font-semibold text-gray-900">Laporan Pertanggungjawaban</h3>
                <button wire:click="create" class="btn btn-primary">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                        </path>
                    </svg>
                    Buat LPJ
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
                        <th>Ketua Panitia</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lpjList as $index => $lpj)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="font-mono">{{ $lpj->nomor_surat ?? '-' }}</td>
                            <td>{{ $lpj->tanggal_surat?->translatedFormat('d F Y') ?? '-' }}</td>
                            <td>{{ $lpj->nama_ketua ?? '-' }}</td>
                            <td class="text-right">
                                <a href="{{ route('admin.kegiatan-ujian.lpj-panitia.print', ['id' => $kegiatanUjian->id, 'lpjId' => $lpj->id]) }}"
                                    target="_blank" class="btn btn-ghost btn-sm">Cetak</a>
                                <button wire:click="edit({{ $lpj->id }})"
                                    class="btn btn-ghost btn-sm">Edit</button>
                                <button wire:click="delete({{ $lpj->id }})"
                                    wire:confirm="Yakin ingin menghapus LPJ ini?"
                                    class="btn btn-ghost btn-sm text-red-600">Hapus</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-8 text-gray-500">Belum ada LPJ.</td>
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
                                    {{ $editingId ? 'Edit' : 'Buat' }} LPJ Panitia
                                </h3>
                            </div>
                            <div class="px-6 py-4 space-y-5">

                                <!-- Nomor & Tanggal -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium mb-1">Nomor Surat
                                            (Opsional)</label>
                                        <input type="text" wire:model="nomor_surat" class="input w-full"
                                            placeholder="Contoh: 001/LPJ/I/2026">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium mb-1">Tanggal</label>
                                        <input type="date" wire:model="tanggal_surat" class="input w-full">
                                        @error('tanggal_surat')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <hr class="border-gray-200">
                                <h4 class="font-semibold text-gray-700 text-sm uppercase tracking-wide">Isi Laporan
                                </h4>

                                <!-- BAB I - Pendahuluan -->
                                <div>
                                    <label class="block text-sm font-medium mb-1">BAB I - Pendahuluan</label>
                                    <div class="quill-wrapper" x-data="quillEditor('pendahuluan')" x-init="initQuill()"
                                        wire:ignore>
                                        <div x-ref="editor"></div>
                                    </div>
                                    @error('pendahuluan')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Dasar Pelaksanaan -->
                                @include('livewire.admin.lpj-panitia-management-partials.dynamic-list', [
                                    'field' => 'dasar_pelaksanaan',
                                    'label' => 'Dasar Pelaksanaan',
                                    'placeholder' => 'Dasar hukum / surat keputusan...',
                                    'numbering' => 'number',
                                ])

                                <!-- Tujuan -->
                                @include('livewire.admin.lpj-panitia-management-partials.dynamic-list', [
                                    'field' => 'tujuan',
                                    'label' => 'Tujuan',
                                    'placeholder' => 'Tujuan kegiatan...',
                                    'numbering' => 'number',
                                ])

                                <!-- Waktu & Tempat -->
                                <div>
                                    <label class="block text-sm font-medium mb-1">Waktu dan Tempat
                                        Pelaksanaan</label>
                                    <div class="quill-wrapper short" x-data="quillEditor('waktu_tempat')" x-init="initQuill()"
                                        wire:ignore>
                                        <div x-ref="editor"></div>
                                    </div>
                                </div>

                                <!-- Susunan Panitia Note -->
                                <div>
                                    <label class="block text-sm font-medium mb-1">Catatan Susunan Panitia
                                        (Opsional)</label>
                                    <div class="quill-wrapper short" x-data="quillEditor('susunan_panitia_text')" x-init="initQuill()"
                                        wire:ignore>
                                        <div x-ref="editor"></div>
                                    </div>
                                    <p class="text-xs text-gray-400 mt-1">Susunan panitia akan otomatis ditampilkan
                                        dari data SK jika tersedia.</p>
                                </div>

                                <hr class="border-gray-200">

                                <!-- BAB II - Pelaksanaan Kegiatan -->
                                @include('livewire.admin.lpj-panitia-management-partials.dynamic-list', [
                                    'field' => 'pelaksanaan_kegiatan',
                                    'label' => 'BAB II - Pelaksanaan Kegiatan',
                                    'placeholder' => 'Uraian pelaksanaan...',
                                    'numbering' => 'number',
                                ])

                                <!-- Hasil Pelaksanaan -->
                                @include('livewire.admin.lpj-panitia-management-partials.dynamic-list', [
                                    'field' => 'hasil_pelaksanaan',
                                    'label' => 'Hasil Pelaksanaan',
                                    'placeholder' => 'Hasil yang dicapai...',
                                    'numbering' => 'number',
                                ])

                                <!-- Kendala/Hambatan -->
                                @include('livewire.admin.lpj-panitia-management-partials.dynamic-list', [
                                    'field' => 'kendala_hambatan',
                                    'label' => 'Kendala / Hambatan',
                                    'placeholder' => 'Kendala yang dihadapi...',
                                    'numbering' => 'number',
                                ])

                                <hr class="border-gray-200">

                                <!-- BAB III - Kesimpulan & Saran -->
                                <div>
                                    <label class="block text-sm font-medium mb-1">BAB III - Kesimpulan</label>
                                    <div class="quill-wrapper" x-data="quillEditor('kesimpulan')" x-init="initQuill()"
                                        wire:ignore>
                                        <div x-ref="editor"></div>
                                    </div>
                                    @error('kesimpulan')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                @include('livewire.admin.lpj-panitia-management-partials.dynamic-list', [
                                    'field' => 'saran',
                                    'label' => 'Saran',
                                    'placeholder' => 'Saran untuk kegiatan serupa...',
                                    'numbering' => 'number',
                                ])

                                <hr class="border-gray-200">

                                <!-- BAB IV - Penutup -->
                                <div>
                                    <label class="block text-sm font-medium mb-1">BAB IV - Penutup</label>
                                    <div class="quill-wrapper" x-data="quillEditor('penutup')" x-init="initQuill()"
                                        wire:ignore>
                                        <div x-ref="editor"></div>
                                    </div>
                                    @error('penutup')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <hr class="border-gray-200">
                                <h4 class="font-semibold text-gray-700 text-sm uppercase tracking-wide">Tanda Tangan
                                </h4>

                                <!-- TTD Section -->
                                <div>
                                    <label class="block text-sm font-medium mb-1">Tempat (TTD)</label>
                                    <input type="text" wire:model="tempat_ttd" class="input w-full"
                                        placeholder="Nama Kota/Kabupaten">
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="space-y-3">
                                        <h5 class="text-sm font-medium text-gray-600">Ketua Panitia</h5>
                                        <div>
                                            <label class="block text-xs text-gray-500 mb-1">Nama</label>
                                            <input type="text" wire:model="nama_ketua" class="input w-full"
                                                placeholder="Nama Ketua Panitia">
                                        </div>
                                        <div>
                                            <label class="block text-xs text-gray-500 mb-1">NIP</label>
                                            <input type="text" wire:model="nip_ketua" class="input w-full"
                                                placeholder="NIP Ketua Panitia">
                                        </div>
                                    </div>
                                    <div class="space-y-3">
                                        <h5 class="text-sm font-medium text-gray-600">Mengetahui, Ketua Panitia
                                        </h5>
                                        <div>
                                            <label class="block text-xs text-gray-500 mb-1">Nama</label>
                                            <input type="text" wire:model="nama_kepala_sekolah"
                                                class="input w-full" placeholder="Nama Ketua Panitia">
                                        </div>
                                        <div>
                                            <label class="block text-xs text-gray-500 mb-1">NIP</label>
                                            <input type="text" wire:model="nip_kepala_sekolah"
                                                class="input w-full" placeholder="NIP Ketua Panitia">
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

    @script
        <script>
            Alpine.data('quillEditor', (field) => ({
                quill: null,
                field: field,

                initQuill() {
                    this.$nextTick(() => {
                        this.quill = new Quill(this.$refs.editor, {
                            theme: 'snow',
                            modules: {
                                toolbar: [
                                    ['bold', 'italic', 'underline'],
                                    [{
                                        'list': 'ordered'
                                    }, {
                                        'list': 'bullet'
                                    }],
                                    [{
                                        'indent': '-1'
                                    }, {
                                        'indent': '+1'
                                    }],
                                    ['clean']
                                ]
                            },
                            placeholder: 'Tulis di sini...',
                        });

                        // Set initial content from Livewire
                        const initialContent = @this.get(this.field);
                        if (initialContent) {
                            this.quill.root.innerHTML = initialContent;
                        }

                        // Sync content back to Livewire on text change
                        this.quill.on('text-change', () => {
                            const html = this.quill.root.innerHTML;
                            @this.set(this.field, html === '<p><br></p>' ? '' : html);
                        });
                    });
                }
            }));
        </script>
    @endscript
</div>
