@push('styles')
    @include('livewire.admin.honor-insentif-kalkulator.styles')
@endpush

<div>
    <x-toast-notification />

    <x-slot name="header">Kalkulator Honor/Insentif</x-slot>

    {{-- Breadcrumb --}}
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
            <span class="font-medium text-gray-900">Kalkulator Honor/Insentif</span>
        </nav>
    </div>

    {{-- Header Info --}}
    <div
        class="mb-6 p-4 rounded-2xl bg-gradient-to-r from-gray-50 via-white to-gray-50 border border-gray-200/60 shadow-sm">
        <div class="flex items-center gap-3">
            <div
                class="w-10 h-10 rounded-xl bg-gradient-to-br from-green-100 to-green-200 flex items-center justify-center">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
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

    @if (!$showPreview)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Input Section --}}
            <div class="lg:col-span-2 space-y-6">
                @include('livewire.admin.honor-insentif-kalkulator.partials.tarif-honor')
                @include('livewire.admin.honor-insentif-kalkulator.partials.daftar-panitia')
                @include('livewire.admin.honor-insentif-kalkulator.partials.daftar-pengawas')
                @include('livewire.admin.honor-insentif-kalkulator.partials.item-tambahan')
            </div>

            {{-- Summary Section --}}
            <div class="lg:col-span-1">
                @include('livewire.admin.honor-insentif-kalkulator.partials.summary')
            </div>
        </div>
    @else
        {{-- Preview & Print --}}
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

        <div class="card overflow-hidden" id="print-area">
            @if (count($daftarPanitia) > 0)
                @include('livewire.admin.honor-insentif-kalkulator.print.honor-panitia', [
                    'kegiatanUjian' => $kegiatanUjian,
                    'schoolSettings' => $schoolSettings,
                    'daftarPanitia' => $daftarPanitia,
                    'honorPerHadirPanitia' => $honorPerHadirPanitia,
                    'totalKehadiranPanitia' => $this->totalKehadiranPanitia,
                    'totalHonorPanitia' => $this->totalHonorPanitia,
                    'terbilang' => $this->terbilang($this->totalHonorPanitia),
                ])
            @endif

            @if (count($daftarPengawas) > 0)
                @include('livewire.admin.honor-insentif-kalkulator.print.honor-pengawas', [
                    'kegiatanUjian' => $kegiatanUjian,
                    'schoolSettings' => $schoolSettings,
                    'daftarPengawas' => $daftarPengawas,
                    'honorPerHadirPengawas' => $honorPerHadirPengawas,
                    'totalKehadiranPengawas' => $this->totalKehadiranPengawas,
                    'totalHonorPengawas' => $this->totalHonorPengawas,
                    'terbilang' => $this->terbilang($this->totalHonorPengawas),
                ])
            @endif

            @if (count($itemTambahan) > 0 && $this->totalItemTambahan > 0)
                @include('livewire.admin.honor-insentif-kalkulator.print.item-tambahan', [
                    'kegiatanUjian' => $kegiatanUjian,
                    'schoolSettings' => $schoolSettings,
                    'itemTambahan' => $itemTambahan,
                    'totalItemTambahan' => $this->totalItemTambahan,
                    'terbilang' => $this->terbilang($this->totalItemTambahan),
                ])
            @endif

            @include('livewire.admin.honor-insentif-kalkulator.print.rekapitulasi', [
                'kegiatanUjian' => $kegiatanUjian,
                'schoolSettings' => $schoolSettings,
                'daftarPanitia' => $daftarPanitia,
                'daftarPengawas' => $daftarPengawas,
                'totalKehadiranPanitia' => $this->totalKehadiranPanitia,
                'totalKehadiranPengawas' => $this->totalKehadiranPengawas,
                'totalHonorPanitia' => $this->totalHonorPanitia,
                'totalHonorPengawas' => $this->totalHonorPengawas,
                'totalItemTambahan' => $this->totalItemTambahan,
                'grandTotal' => $this->grandTotal,
                'terbilang' => $this->terbilang($this->grandTotal),
            ])
        </div>
    @endif
</div>
