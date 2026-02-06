{{-- Tarif Honor Section --}}
<div class="card overflow-hidden">
    <div class="p-4 border-b border-gray-100 bg-gradient-to-r from-indigo-50 to-white">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center">
                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                    </path>
                </svg>
            </div>
            <div>
                <h3 class="text-base font-semibold text-gray-900">Tarif Honor per Kehadiran</h3>
                <p class="text-xs text-gray-500">Tentukan besaran honor per kedatangan</p>
            </div>
        </div>
    </div>
    <div class="p-5">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="form-label">Honor Panitia per Kehadiran</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Rp</span>
                    <input type="number" wire:model.blur="honorPerHadirPanitia" class="form-input w-full pl-10"
                        min="0" placeholder="0">
                </div>
                <p class="text-xs text-gray-400 mt-1">per orang/kehadiran</p>
            </div>
            <div>
                <label class="form-label">Honor Pengawas per Kehadiran</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Rp</span>
                    <input type="number" wire:model.blur="honorPerHadirPengawas" class="form-input w-full pl-10"
                        min="0" placeholder="0">
                </div>
                <p class="text-xs text-gray-400 mt-1">per orang/kehadiran mengawas</p>
            </div>
        </div>
    </div>
</div>
