<div>
    <x-slot name="header">Tata Tertib Ujian</x-slot>

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
            <span class="font-medium text-gray-900">Tata Tertib Ujian</span>
        </nav>
    </div>

    <!-- Header Info -->
    <div
        class="mb-6 p-4 rounded-2xl bg-gradient-to-r from-gray-50 via-white to-gray-50 border border-gray-200/60 shadow-sm">
        <div class="flex items-center gap-3">
            <div
                class="w-10 h-10 rounded-xl bg-gradient-to-br from-rose-100 to-rose-200 flex items-center justify-center">
                <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                    </path>
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-gray-900">{{ $kegiatanUjian->nama_ujian }}</h2>
                <p class="text-sm text-gray-500">{{ $kegiatanUjian->tahunAjaran->nama }} ·
                    {{ $kegiatanUjian->tahunAjaran->semester }}</p>
            </div>
        </div>
    </div>

    <!-- Tab Selection -->
    <div class="mb-6 flex gap-3">
        <button wire:click="setViewMode('peserta')"
            class="group flex items-center gap-2 px-5 py-2.5 rounded-xl font-medium transition-all duration-300 {{ $viewMode === 'peserta' ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-lg shadow-blue-500/25' : 'bg-white text-gray-600 border border-gray-200 hover:border-blue-300 hover:text-blue-600' }}">
            <svg class="w-5 h-5 {{ $viewMode === 'peserta' ? '' : 'group-hover:scale-110' }} transition-transform"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                </path>
            </svg>
            Tata Tertib Peserta
        </button>
        <button wire:click="setViewMode('pengawas')"
            class="group flex items-center gap-2 px-5 py-2.5 rounded-xl font-medium transition-all duration-300 {{ $viewMode === 'pengawas' ? 'bg-gradient-to-r from-emerald-500 to-emerald-600 text-white shadow-lg shadow-emerald-500/25' : 'bg-white text-gray-600 border border-gray-200 hover:border-emerald-300 hover:text-emerald-600' }}">
            <svg class="w-5 h-5 {{ $viewMode === 'pengawas' ? '' : 'group-hover:scale-110' }} transition-transform"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                </path>
            </svg>
            Tata Tertib Pengawas
        </button>
    </div>

    @if (!$showPreview)
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Editor -->
            <div class="card overflow-hidden">
                <div class="p-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Edit Tata Tertib {{ $viewMode === 'peserta' ? 'Peserta' : 'Pengawas' }}
                    </h3>
                    <p class="text-sm text-gray-500 mt-0.5">Satu poin per baris, ubah sesuai kebutuhan</p>
                </div>
                <div class="p-5 space-y-4">
                    @if ($viewMode === 'peserta')
                        <textarea wire:model="tataTertibPeserta" class="form-textarea w-full" rows="15"
                            placeholder="Masukkan tata tertib peserta (satu poin per baris)"></textarea>
                    @else
                        <textarea wire:model="tataTertibPengawas" class="form-textarea w-full" rows="15"
                            placeholder="Masukkan tata tertib pengawas (satu poin per baris)"></textarea>
                    @endif
                    <div class="flex flex-wrap gap-3">
                        <button wire:click="saveContent"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4">
                                </path>
                            </svg>
                            Simpan
                        </button>
                        <button wire:click="resetToDefault"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-amber-300 rounded-xl text-sm font-medium text-amber-700 hover:bg-amber-50 hover:border-amber-400 transition-all duration-200 shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                </path>
                            </svg>
                            Reset Default
                        </button>
                        <button wire:click="togglePreview"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl text-sm font-medium text-white hover:from-blue-600 hover:to-blue-700 transition-all duration-200 shadow-sm shadow-blue-500/25">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                </path>
                            </svg>
                            Preview & Cetak
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mini Preview -->
            <div class="card overflow-hidden">
                <div class="p-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                    <h3 class="text-lg font-semibold text-gray-900">Preview</h3>
                    <p class="text-sm text-gray-500 mt-0.5">Tampilan dokumen yang akan dicetak</p>
                </div>
                <div class="p-5 bg-white">
                    <div class="text-center mb-5 pb-4 border-b border-gray-100">
                        <h4 class="font-bold text-base text-gray-900">
                            TATA TERTIB {{ $viewMode === 'peserta' ? 'PESERTA' : 'PENGAWAS' }} UJIAN
                        </h4>
                        <p class="text-sm text-gray-500 mt-1">{{ $kegiatanUjian->nama_ujian }}</p>
                    </div>
                    <ol class="list-decimal list-outside ml-5 space-y-2.5 text-sm text-gray-700">
                        @foreach ($tataTertibList as $item)
                            <li class="pl-1">{{ trim($item) }}</li>
                        @endforeach
                    </ol>
                </div>
            </div>
        </div>
    @else
        <!-- Full Preview & Print -->
        <div class="mb-4 flex items-center gap-3">
            <button wire:click="togglePreview"
                class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </button>
            <button onclick="window.print()"
                class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-xl text-sm font-medium text-white hover:from-emerald-600 hover:to-emerald-700 transition-all duration-200 shadow-sm shadow-emerald-500/25">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                    </path>
                </svg>
                Cetak PDF
            </button>
        </div>

        <div class="card" id="print-area">
            <div class="p-8 print-content">
                <!-- Kop Surat -->
                <div class="flex items-center gap-4 border-b-4 border-double border-black pb-3 mb-6">
                    <div class="w-20 h-20 flex-shrink-0">
                        @if (!empty($schoolSettings['logo']))
                            <img src="{{ asset('storage/' . $schoolSettings['logo']) }}" alt="Logo"
                                class="w-full h-full object-contain">
                        @else
                            <svg viewBox="0 0 24 24" fill="#666" class="w-full h-full">
                                <path
                                    d="M12 3L1 9l4 2.18v6L12 21l7-3.82v-6l2-1.09V17h2V9L12 3zm6.82 6L12 12.72 5.18 9 12 5.28 18.82 9zM17 15.99l-5 2.73-5-2.73v-3.72L12 15l5-2.73v3.72z" />
                            </svg>
                        @endif
                    </div>
                    <div class="flex-1 text-center">
                        <div class="text-sm font-bold">KEMENTERIAN AGAMA REPUBLIK INDONESIA</div>
                        <div class="text-lg font-bold">
                            {{ strtoupper($schoolSettings['nama_sekolah'] ?? 'NAMA SEKOLAH') }}</div>
                        <div class="text-xs">Alamat: {{ $schoolSettings['alamat'] ?? '' }}
                            {{ $schoolSettings['kelurahan'] ?? '' }} {{ $schoolSettings['kecamatan'] ?? '' }}
                            Telp. {{ $schoolSettings['telepon'] ?? '' }}</div>
                        <div class="text-xs">{{ strtoupper($schoolSettings['kabupaten'] ?? '') }}
                            {{ $schoolSettings['kode_pos'] ?? '' }}</div>
                    </div>
                </div>

                <!-- Title -->
                <div class="text-center mb-6">
                    <h2 class="text-xl font-bold border-b-2 border-black inline-block px-6 pb-1">
                        TATA TERTIB {{ $viewMode === 'peserta' ? 'PESERTA' : 'PENGAWAS' }} UJIAN
                    </h2>
                    <p class="text-sm mt-3 font-semibold">{{ strtoupper($kegiatanUjian->nama_ujian) }}</p>
                    <p class="text-sm">Tahun Ajaran {{ $kegiatanUjian->tahunAjaran->nama }}</p>
                </div>

                <!-- Content -->
                <div class="text-sm leading-relaxed">
                    <ol class="list-decimal list-outside ml-6 space-y-3">
                        @foreach ($tataTertibList as $item)
                            <li class="text-justify">{{ trim($item) }}</li>
                        @endforeach
                    </ol>
                </div>

                <!-- Footer -->
                <div class="mt-10 text-sm">
                    <p class="italic text-center">
                        "Tata tertib ini wajib dipatuhi oleh seluruh
                        {{ $viewMode === 'peserta' ? 'peserta' : 'pengawas' }} ujian"
                    </p>
                </div>

                <div class="mt-8 flex justify-end">
                    <div class="text-center text-sm">
                        <p>{{ $schoolSettings['kabupaten'] ?? '' }}, .........................</p>
                        <p class="font-bold mt-1">Kepala {{ $schoolSettings['nama_sekolah'] ?? 'Sekolah' }}</p>
                        <div class="h-20"></div>
                        <p class="font-semibold border-b border-black inline-block px-4">________________________</p>
                        <p class="text-xs mt-1">NIP. ________________________</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Print Styles -->
    <style>
        @media print {
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            body * {
                visibility: hidden;
            }

            #print-area,
            #print-area * {
                visibility: visible;
            }

            #print-area {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                padding: 15mm;
                box-shadow: none !important;
                border: none !important;
            }

            @page {
                size: A4 portrait;
                margin: 10mm;
            }

            .card {
                box-shadow: none !important;
                border: none !important;
            }
        }
    </style>
</div>
