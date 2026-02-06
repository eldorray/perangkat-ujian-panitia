<div>
    <x-slot name="header">POS Ujian</x-slot>

    <!-- Breadcrumb & Info -->
    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm text-gray-500 mb-3">
            <a href="{{ route('admin.kegiatan-ujian') }}" class="hover:text-gray-700" wire:navigate>Kegiatan Ujian</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            <a href="{{ route('admin.kegiatan-ujian.perangkat', $kegiatanUjian->id) }}" class="hover:text-gray-700"
                wire:navigate>Perangkat</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            <span class="text-gray-900">POS</span>
        </div>

        <div class="card p-4">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">{{ $kegiatanUjian->nama_ujian }}</h2>
                    <span class="badge badge-secondary mt-1">
                        {{ $kegiatanUjian->tahunAjaran->nama }} - {{ $kegiatanUjian->tahunAjaran->semester }}
                    </span>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.kegiatan-ujian.perangkat', $kegiatanUjian->id) }}"
                        class="btn btn-secondary" wire:navigate>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="card p-8">
        <div class="text-center max-w-2xl mx-auto">
            <!-- Icon -->
            <div class="mb-6">
                <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto">
                    <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                </div>
            </div>

            <!-- Title & Description -->
            <h3 class="text-xl font-bold text-gray-900 mb-3">Prosedur Operasional Standar (POS) Ujian</h3>
            <p class="text-gray-600 mb-8">
                Dokumen POS Ujian berisi panduan lengkap mengenai prosedur pelaksanaan ujian, termasuk tata tertib,
                jadwal, dan ketentuan lainnya. Klik tombol di bawah untuk membuka dokumen referensi POS.
            </p>

            <!-- Button -->
            <a href="{{ $posReferenceUrl }}" target="_blank" rel="noopener noreferrer"
                class="btn btn-primary inline-flex items-center gap-2 px-6 py-3 text-base">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                </svg>
                Buka Referensi POS
            </a>

            <!-- Info -->
            <p class="text-sm text-gray-500 mt-6">
                <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Dokumen akan terbuka di tab baru (Google Docs)
            </p>
        </div>
    </div>
</div>
