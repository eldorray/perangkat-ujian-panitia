{{-- Summary Section --}}
<div class="card overflow-hidden sticky top-6">
    <div class="p-4 border-b border-gray-100 bg-gradient-to-r from-gray-900 to-gray-800">
        <h3 class="text-base font-semibold text-white">Ringkasan</h3>
        <p class="text-xs text-gray-300">Total kalkulasi honor/insentif</p>
    </div>
    <div class="p-5 space-y-4">
        {{-- Panitia --}}
        <div class="flex items-center justify-between py-2 border-b border-gray-100">
            <div class="flex items-center gap-2">
                <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                <span class="text-sm text-gray-600">Honor Panitia</span>
            </div>
            <span class="text-sm font-medium text-gray-900">Rp
                {{ number_format($this->totalHonorPanitia, 0, ',', '.') }}</span>
        </div>

        {{-- Pengawas --}}
        <div class="flex items-center justify-between py-2 border-b border-gray-100">
            <div class="flex items-center gap-2">
                <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                <span class="text-sm text-gray-600">Honor Pengawas</span>
            </div>
            <span class="text-sm font-medium text-gray-900">Rp
                {{ number_format($this->totalHonorPengawas, 0, ',', '.') }}</span>
        </div>

        {{-- Item Tambahan --}}
        @if ($this->totalItemTambahan > 0)
            <div class="flex items-center justify-between py-2 border-b border-gray-100">
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-amber-500"></div>
                    <span class="text-sm text-gray-600">Item Tambahan</span>
                </div>
                <span class="text-sm font-medium text-gray-900">Rp
                    {{ number_format($this->totalItemTambahan, 0, ',', '.') }}</span>
            </div>
        @endif

        {{-- Grand Total --}}
        <div class="p-4 -mx-5 -mb-5 bg-gradient-to-r from-green-500 to-emerald-600">
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-white/90">GRAND TOTAL</span>
                <span class="text-2xl font-bold text-white">Rp
                    {{ number_format($this->grandTotal, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    {{-- Actions --}}
    <div class="p-4 border-t border-gray-100 bg-gray-50 space-y-3">
        <button wire:click="saveToSession"
            class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4">
                </path>
            </svg>
            Simpan Data
        </button>
        <button wire:click="togglePreview"
            class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl text-sm font-medium text-white hover:from-blue-600 hover:to-blue-700 transition-all duration-200 shadow-sm shadow-blue-500/25">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                </path>
            </svg>
            Preview & Cetak
        </button>
    </div>
</div>
