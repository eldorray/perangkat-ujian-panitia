<div>
    <x-slot name="header">Menu Panitia</x-slot>

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
            <span class="font-medium text-gray-900">Menu Panitia</span>
        </nav>
    </div>

    <!-- Header Info -->
    <div class="mb-6 p-4 rounded-xl bg-gray-50 border border-gray-200">
        <h2 class="text-lg font-semibold text-gray-900">{{ $kegiatanUjian->nama_ujian }}</h2>
        <p class="text-sm text-gray-500 mt-1">{{ $kegiatanUjian->tahunAjaran->nama }} -
            {{ $kegiatanUjian->tahunAjaran->semester }}</p>
    </div>

    @if ($viewMode === 'list')
        <!-- Menu Selection -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            <!-- Daftar Hadir Panitia Card -->
            <div class="group card p-5 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 cursor-pointer border-l-4 border-l-blue-500 hover:border-l-blue-600"
                wire:click="setViewMode('panitia')">
                <div class="flex items-start gap-4">
                    <div
                        class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-base font-semibold text-gray-900 group-hover:text-blue-600 transition-colors">
                            Daftar Hadir Panitia</h3>
                        <p class="text-sm text-gray-500 mt-0.5 line-clamp-2">Buat daftar hadir untuk panitia ujian</p>
                        @if (count($selectedPanitia) > 0)
                            <span
                                class="inline-flex items-center mt-2 px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">{{ count($selectedPanitia) }}
                                guru dipilih</span>
                        @endif
                    </div>
                    <svg class="w-5 h-5 text-gray-300 group-hover:text-blue-500 group-hover:translate-x-1 transition-all duration-300"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </div>

            <!-- Daftar Hadir Pengawas Card -->
            <div class="group card p-5 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 cursor-pointer border-l-4 border-l-emerald-500 hover:border-l-emerald-600"
                wire:click="setViewMode('pengawas')">
                <div class="flex items-start gap-4">
                    <div
                        class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-100 to-emerald-200 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                            </path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3
                            class="text-base font-semibold text-gray-900 group-hover:text-emerald-600 transition-colors">
                            Daftar Hadir Pengawas</h3>
                        <p class="text-sm text-gray-500 mt-0.5 line-clamp-2">Buat daftar hadir untuk pengawas ujian</p>
                        @if (count($selectedPengawas) > 0)
                            <span
                                class="inline-flex items-center mt-2 px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">{{ count($selectedPengawas) }}
                                guru dipilih</span>
                        @endif
                    </div>
                    <svg class="w-5 h-5 text-gray-300 group-hover:text-emerald-500 group-hover:translate-x-1 transition-all duration-300"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </div>

            <!-- Jadwal Mengawas Card -->
            <a href="{{ route('admin.kegiatan-ujian.jadwal-mengawas', $kegiatanUjian->id) }}" wire:navigate
                class="group card p-5 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 cursor-pointer border-l-4 border-l-violet-500 hover:border-l-violet-600">
                <div class="flex items-start gap-4">
                    <div
                        class="w-12 h-12 rounded-xl bg-gradient-to-br from-violet-100 to-violet-200 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                            </path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-base font-semibold text-gray-900 group-hover:text-violet-600 transition-colors">
                            Jadwal Mengawas</h3>
                        <p class="text-sm text-gray-500 mt-0.5 line-clamp-2">Buat jadwal penugasan pengawas per ruangan
                        </p>
                    </div>
                    <svg class="w-5 h-5 text-gray-300 group-hover:text-violet-500 group-hover:translate-x-1 transition-all duration-300"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </a>

            <!-- Berita Acara Ujian Card -->
            <a href="{{ route('admin.kegiatan-ujian.berita-acara', $kegiatanUjian->id) }}" wire:navigate
                class="group card p-5 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 cursor-pointer border-l-4 border-l-amber-500 hover:border-l-amber-600">
                <div class="flex items-start gap-4">
                    <div
                        class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-100 to-amber-200 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-base font-semibold text-gray-900 group-hover:text-amber-600 transition-colors">
                            Berita Acara Ujian</h3>
                        <p class="text-sm text-gray-500 mt-0.5 line-clamp-2">Buat berita acara per sesi ujian</p>
                    </div>
                    <svg class="w-5 h-5 text-gray-300 group-hover:text-amber-500 group-hover:translate-x-1 transition-all duration-300"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                        </path>
                    </svg>
                </div>
            </a>

            <!-- Label Amplop Soal Card -->
            <a href="{{ route('admin.kegiatan-ujian.label-amplop', $kegiatanUjian->id) }}" wire:navigate
                class="group card p-5 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 cursor-pointer border-l-4 border-l-cyan-500 hover:border-l-cyan-600">
                <div class="flex items-start gap-4">
                    <div
                        class="w-12 h-12 rounded-xl bg-gradient-to-br from-cyan-100 to-cyan-200 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z">
                            </path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-base font-semibold text-gray-900 group-hover:text-cyan-600 transition-colors">
                            Label Amplop Soal</h3>
                        <p class="text-sm text-gray-500 mt-0.5 line-clamp-2">Cetak label untuk amplop soal per ruangan
                        </p>
                    </div>
                    <svg class="w-5 h-5 text-gray-300 group-hover:text-cyan-500 group-hover:translate-x-1 transition-all duration-300"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                        </path>
                    </svg>
                </div>
            </a>

            <!-- Tata Tertib Ujian Card -->
            <a href="{{ route('admin.kegiatan-ujian.tata-tertib', $kegiatanUjian->id) }}" wire:navigate
                class="group card p-5 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 cursor-pointer border-l-4 border-l-rose-500 hover:border-l-rose-600">
                <div class="flex items-start gap-4">
                    <div
                        class="w-12 h-12 rounded-xl bg-gradient-to-br from-rose-100 to-rose-200 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-base font-semibold text-gray-900 group-hover:text-rose-600 transition-colors">
                            Tata Tertib Ujian</h3>
                        <p class="text-sm text-gray-500 mt-0.5 line-clamp-2">Buat dan cetak tata tertib peserta &
                            pengawas</p>
                    </div>
                    <svg class="w-5 h-5 text-gray-300 group-hover:text-rose-500 group-hover:translate-x-1 transition-all duration-300"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                        </path>
                    </svg>
                </div>
            </a>

            <!-- Kalkulator Honor/Insentif Card -->
            <a href="{{ route('admin.kegiatan-ujian.honor-insentif', $kegiatanUjian->id) }}" wire:navigate
                class="group card p-5 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 cursor-pointer border-l-4 border-l-green-500 hover:border-l-green-600">
                <div class="flex items-start gap-4">
                    <div
                        class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-100 to-green-200 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-base font-semibold text-gray-900 group-hover:text-green-600 transition-colors">
                            Kalkulator Honor/Insentif</h3>
                        <p class="text-sm text-gray-500 mt-0.5 line-clamp-2">Hitung honor panitia dan pengawas ujian
                        </p>
                    </div>
                    <svg class="w-5 h-5 text-gray-300 group-hover:text-green-500 group-hover:translate-x-1 transition-all duration-300"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                        </path>
                    </svg>
                </div>
            </a>

            <!-- Surat Keputusan Card -->
            <a href="{{ route('admin.kegiatan-ujian.surat-keputusan', $kegiatanUjian->id) }}" wire:navigate
                class="group card p-5 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 cursor-pointer border-l-4 border-l-indigo-500 hover:border-l-indigo-600">
                <div class="flex items-start gap-4">
                    <div
                        class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-100 to-indigo-200 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                            </path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3
                            class="text-base font-semibold text-gray-900 group-hover:text-indigo-600 transition-colors">
                            Surat Keputusan</h3>
                        <p class="text-sm text-gray-500 mt-0.5 line-clamp-2">Buat SK penetapan panitia ujian beserta
                            lampiran</p>
                    </div>
                    <svg class="w-5 h-5 text-gray-300 group-hover:text-indigo-500 group-hover:translate-x-1 transition-all duration-300"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                        </path>
                    </svg>
                </div>
            </a>

            <!-- LPJ Panitia Card -->
            <a href="{{ route('admin.kegiatan-ujian.lpj-panitia', $kegiatanUjian->id) }}" wire:navigate
                class="group card p-5 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 cursor-pointer border-l-4 border-l-teal-500 hover:border-l-teal-600">
                <div class="flex items-start gap-4">
                    <div
                        class="w-12 h-12 rounded-xl bg-gradient-to-br from-teal-100 to-teal-200 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-base font-semibold text-gray-900 group-hover:text-teal-600 transition-colors">
                            LPJ Panitia</h3>
                        <p class="text-sm text-gray-500 mt-0.5 line-clamp-2">Laporan pertanggungjawaban pelaksanaan
                            kegiatan ujian</p>
                    </div>
                    <svg class="w-5 h-5 text-gray-300 group-hover:text-teal-500 group-hover:translate-x-1 transition-all duration-300"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                        </path>
                    </svg>
                </div>
            </a>
        </div>
    @elseif ($viewMode === 'panitia')
        <!-- Daftar Hadir Panitia View -->
        <div class="mb-4">
            <button wire:click="backToList" class="btn btn-secondary">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Pilih Guru -->
            <div class="card">
                <div class="p-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Pilih Guru Panitia</h3>
                    <p class="text-sm text-gray-500 mt-1">Centang guru yang akan ditampilkan di daftar hadir</p>
                </div>

                <div class="p-4 border-b border-gray-200">
                    <div class="flex flex-col sm:flex-row gap-3">
                        <input type="text" wire:model.live.debounce.300ms="search" class="form-input flex-1"
                            placeholder="Cari nama atau NIP...">
                        <div class="flex gap-2">
                            <button wire:click="selectAllPanitia" class="btn btn-secondary btn-sm">Pilih
                                Semua</button>
                            <button wire:click="clearPanitia" class="btn btn-secondary btn-sm">Hapus Semua</button>
                        </div>
                    </div>
                </div>

                <div class="p-4 max-h-[500px] overflow-y-auto">
                    @forelse ($guruList as $guru)
                        <label
                            class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-0">
                            <input type="checkbox" wire:click="togglePanitia({{ $guru->id }})"
                                @checked(in_array($guru->id, $selectedPanitia)) class="form-checkbox h-5 w-5 text-blue-600 rounded">
                            <div class="flex-1">
                                <p class="font-medium text-gray-900">{{ $guru->full_name_with_titles }}</p>
                                <p class="text-sm text-gray-500">{{ $guru->nip ?: '-' }}</p>
                            </div>
                        </label>
                    @empty
                        <p class="text-center text-gray-500 py-8">Tidak ada guru ditemukan</p>
                    @endforelse
                </div>
            </div>

            <!-- Preview & Print -->
            <div class="card" id="panitia-print-area">
                <div class="p-4 border-b border-gray-200 flex items-center justify-between print:hidden">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Preview Daftar Hadir Panitia</h3>
                        <p class="text-sm text-gray-500">{{ count($selectedPanitia) }} guru dipilih</p>
                    </div>
                    @if (count($selectedPanitia) > 0)
                        <button onclick="printPanitia()" class="btn btn-primary">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                </path>
                            </svg>
                            Cetak PDF
                        </button>
                    @endif
                </div>

                <div class="p-6 print-content">
                    @if (count($selectedPanitia) > 0)
                        <!-- Kop Surat -->
                        <div class="flex items-center gap-4 border-b-4 border-double border-black pb-3 mb-4">
                            <div class="w-16 h-16 flex-shrink-0">
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
                                <div class="text-xs font-bold">KEMENTERIAN AGAMA REPUBLIK INDONESIA</div>
                                <div class="text-base font-bold">
                                    {{ strtoupper($schoolSettings['nama_sekolah'] ?? 'NAMA SEKOLAH') }}</div>
                                <div class="text-xs">Alamat: {{ $schoolSettings['alamat'] ?? '' }}
                                    {{ $schoolSettings['kelurahan'] ?? '' }} {{ $schoolSettings['kecamatan'] ?? '' }}
                                    Telp. {{ $schoolSettings['telepon'] ?? '' }}</div>
                                <div class="text-xs">{{ strtoupper($schoolSettings['kabupaten'] ?? '') }}
                                    {{ $schoolSettings['kode_pos'] ?? '' }}</div>
                            </div>
                        </div>

                        <!-- Title -->
                        <div class="text-center mb-4">
                            <h2 class="text-lg font-bold">DAFTAR HADIR PANITIA</h2>
                            <p class="text-sm">{{ $kegiatanUjian->nama_ujian }}</p>
                            <p class="text-sm">Tahun Ajaran {{ $kegiatanUjian->tahunAjaran->nama }}</p>
                            @if ($tanggalUjian->count() > 0)
                                <p class="text-sm">Tanggal
                                    {{ \Carbon\Carbon::parse($tanggalUjian->first())->translatedFormat('d M Y') }} s.d.
                                    {{ \Carbon\Carbon::parse($tanggalUjian->last())->translatedFormat('d M Y') }}</p>
                            @endif
                        </div>

                        <!-- Table -->
                        <table class="w-full border-collapse border border-black text-sm">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="border border-black px-2 py-2 w-10">No</th>
                                    <th class="border border-black px-2 py-2">Nama</th>
                                    <th class="border border-black px-2 py-2 w-28">NIP</th>
                                    @if ($tanggalUjian->count() > 0)
                                        @foreach ($tanggalUjian as $tanggal)
                                            <th class="border border-black px-1 py-2 text-center text-xs"
                                                style="min-width: 50px;">
                                                {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d M') }}
                                            </th>
                                        @endforeach
                                    @else
                                        <th class="border border-black px-2 py-2 w-24">Tanda Tangan</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($selectedPanitiaData as $index => $guru)
                                    <tr>
                                        <td class="border border-black px-2 py-3 text-center">{{ $index + 1 }}</td>
                                        <td class="border border-black px-2 py-3">{{ $guru->full_name_with_titles }}
                                        </td>
                                        <td class="border border-black px-2 py-3 text-center text-xs">
                                            {{ $guru->nip ?: '-' }}</td>
                                        @if ($tanggalUjian->count() > 0)
                                            @foreach ($tanggalUjian as $tanggal)
                                                <td class="border border-black px-1 py-3 h-12"></td>
                                            @endforeach
                                        @else
                                            <td class="border border-black px-2 py-3 h-12"></td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Footer -->
                        <div class="mt-8 flex justify-end">
                            <div class="text-center">
                                <p class="text-sm">{{ $schoolSettings['kabupaten'] ?? '' }}, .........................
                                </p>
                                <p class="text-sm font-bold mt-1">Ketua Panitia</p>
                                <div class="h-20"></div>
                                <p class="text-sm">____________________________</p>
                                <p class="text-sm">NIP. ................................</p>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-12 text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-300 mb-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                </path>
                            </svg>
                            <p>Pilih guru untuk melihat preview daftar hadir</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @elseif ($viewMode === 'pengawas')
        <!-- Daftar Hadir Pengawas View -->
        <div class="mb-4">
            <button wire:click="backToList" class="btn btn-secondary">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Pilih Guru -->
            <div class="card">
                <div class="p-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Pilih Guru Pengawas</h3>
                    <p class="text-sm text-gray-500 mt-1">Centang guru yang akan ditampilkan di daftar hadir</p>
                </div>

                <div class="p-4 border-b border-gray-200">
                    <div class="flex flex-col sm:flex-row gap-3">
                        <input type="text" wire:model.live.debounce.300ms="search" class="form-input flex-1"
                            placeholder="Cari nama atau NIP...">
                        <div class="flex gap-2">
                            <button wire:click="selectAllPengawas" class="btn btn-secondary btn-sm">Pilih
                                Semua</button>
                            <button wire:click="clearPengawas" class="btn btn-secondary btn-sm">Hapus Semua</button>
                        </div>
                    </div>
                </div>

                <div class="p-4 max-h-[500px] overflow-y-auto">
                    @forelse ($guruList as $guru)
                        <label
                            class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-0">
                            <input type="checkbox" wire:click="togglePengawas({{ $guru->id }})"
                                @checked(in_array($guru->id, $selectedPengawas)) class="form-checkbox h-5 w-5 text-green-600 rounded">
                            <div class="flex-1">
                                <p class="font-medium text-gray-900">{{ $guru->full_name_with_titles }}</p>
                                <p class="text-sm text-gray-500">{{ $guru->nip ?: '-' }}</p>
                            </div>
                        </label>
                    @empty
                        <p class="text-center text-gray-500 py-8">Tidak ada guru ditemukan</p>
                    @endforelse
                </div>
            </div>

            <!-- Preview & Print -->
            <div class="card" id="pengawas-print-area">
                <div class="p-4 border-b border-gray-200 flex items-center justify-between print:hidden">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Preview Daftar Hadir Pengawas</h3>
                        <p class="text-sm text-gray-500">{{ count($selectedPengawas) }} guru dipilih</p>
                    </div>
                    @if (count($selectedPengawas) > 0)
                        <button onclick="printPengawas()" class="btn btn-primary">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                </path>
                            </svg>
                            Cetak PDF
                        </button>
                    @endif
                </div>

                <div class="p-6 print-content">
                    @if (count($selectedPengawas) > 0)
                        <!-- Kop Surat -->
                        <div class="flex items-center gap-4 border-b-4 border-double border-black pb-3 mb-4">
                            <div class="w-16 h-16 flex-shrink-0">
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
                                <div class="text-xs font-bold">KEMENTERIAN AGAMA REPUBLIK INDONESIA</div>
                                <div class="text-base font-bold">
                                    {{ strtoupper($schoolSettings['nama_sekolah'] ?? 'NAMA SEKOLAH') }}</div>
                                <div class="text-xs">Alamat: {{ $schoolSettings['alamat'] ?? '' }}
                                    {{ $schoolSettings['kelurahan'] ?? '' }} {{ $schoolSettings['kecamatan'] ?? '' }}
                                    Telp. {{ $schoolSettings['telepon'] ?? '' }}</div>
                                <div class="text-xs">{{ strtoupper($schoolSettings['kabupaten'] ?? '') }}
                                    {{ $schoolSettings['kode_pos'] ?? '' }}</div>
                            </div>
                        </div>

                        <!-- Title -->
                        <div class="text-center mb-4">
                            <h2 class="text-lg font-bold">DAFTAR HADIR PENGAWAS UJIAN</h2>
                            <p class="text-sm">{{ $kegiatanUjian->nama_ujian }}</p>
                            <p class="text-sm">Tahun Ajaran {{ $kegiatanUjian->tahunAjaran->nama }}</p>
                            @if ($tanggalUjian->count() > 0)
                                <p class="text-sm">Tanggal
                                    {{ \Carbon\Carbon::parse($tanggalUjian->first())->translatedFormat('d M Y') }} s.d.
                                    {{ \Carbon\Carbon::parse($tanggalUjian->last())->translatedFormat('d M Y') }}</p>
                            @endif
                        </div>

                        <!-- Table -->
                        <table class="w-full border-collapse border border-black text-sm">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="border border-black px-2 py-2 w-10">No</th>
                                    <th class="border border-black px-2 py-2">Nama</th>
                                    <th class="border border-black px-2 py-2 w-28">NIP</th>
                                    @if ($tanggalUjian->count() > 0)
                                        @foreach ($tanggalUjian as $tanggal)
                                            <th class="border border-black px-1 py-2 text-center text-xs"
                                                style="min-width: 50px;">
                                                {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d M') }}
                                            </th>
                                        @endforeach
                                    @else
                                        <th class="border border-black px-2 py-2 w-24">Tanda Tangan</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($selectedPengawasData as $index => $guru)
                                    <tr>
                                        <td class="border border-black px-2 py-3 text-center">{{ $index + 1 }}</td>
                                        <td class="border border-black px-2 py-3">{{ $guru->full_name_with_titles }}
                                        </td>
                                        <td class="border border-black px-2 py-3 text-center text-xs">
                                            {{ $guru->nip ?: '-' }}</td>
                                        @if ($tanggalUjian->count() > 0)
                                            @foreach ($tanggalUjian as $tanggal)
                                                <td class="border border-black px-1 py-3 h-12"></td>
                                            @endforeach
                                        @else
                                            <td class="border border-black px-2 py-3 h-12"></td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Footer -->
                        <div class="mt-8 flex justify-end">
                            <div class="text-center">
                                <p class="text-sm">{{ $schoolSettings['kabupaten'] ?? '' }}, .........................
                                </p>
                                <p class="text-sm font-bold mt-1">Ketua Panitia</p>
                                <div class="h-20"></div>
                                <p class="text-sm">____________________________</p>
                                <p class="text-sm">NIP. ................................</p>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-12 text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-300 mb-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                </path>
                            </svg>
                            <p>Pilih guru untuk melihat preview daftar hadir</p>
                        </div>
                    @endif
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

            .print-content,
            .print-content * {
                visibility: visible;
            }

            .print-content {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                padding: 10mm;
            }

            .print\:hidden {
                display: none !important;
            }

            @page {
                size: A4 portrait;
                margin: 10mm;
            }
        }
    </style>

    <script>
        function printPanitia() {
            const printContent = document.querySelector('#panitia-print-area .print-content');
            const originalContent = document.body.innerHTML;

            document.body.innerHTML = printContent.innerHTML;
            window.print();
            document.body.innerHTML = originalContent;

            // Reload to restore Livewire
            window.location.reload();
        }

        function printPengawas() {
            const printContent = document.querySelector('#pengawas-print-area .print-content');
            const originalContent = document.body.innerHTML;

            document.body.innerHTML = printContent.innerHTML;
            window.print();
            document.body.innerHTML = originalContent;

            // Reload to restore Livewire
            window.location.reload();
        }
    </script>
</div>
