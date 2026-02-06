{{-- Item Tambahan Section --}}
<div class="card overflow-hidden">
    <div class="p-4 border-b border-gray-100 bg-gradient-to-r from-amber-50 to-white">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center">
                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-semibold text-gray-900">Item Tambahan</h3>
                    <p class="text-xs text-gray-500">Honor/insentif lainnya (opsional)</p>
                </div>
            </div>
            <button wire:click="addItemTambahan"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-100 text-amber-700 rounded-lg text-sm font-medium hover:bg-amber-200 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah
            </button>
        </div>
    </div>
    <div class="p-5">
        @if (count($itemTambahan) === 0)
            <div class="text-center py-8 text-gray-400">
                <svg class="mx-auto h-10 w-10 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                    </path>
                </svg>
                <p class="text-sm">Belum ada item tambahan</p>
                <p class="text-xs mt-1">Klik tombol "Tambah" untuk menambahkan</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach ($itemTambahan as $index => $item)
                    <div class="p-4 rounded-xl bg-gray-50 border border-gray-100 relative group">
                        <button wire:click="removeItemTambahan({{ $index }})"
                            class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center hover:bg-red-600">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                            <div class="md:col-span-2">
                                <label class="form-label text-xs">Nama Item</label>
                                <input type="text" wire:model.blur="itemTambahan.{{ $index }}.nama"
                                    class="form-input w-full text-sm" placeholder="Contoh: Honor Ketua Panitia">
                            </div>
                            <div>
                                <label class="form-label text-xs">Jumlah</label>
                                <div class="flex gap-2">
                                    <input type="number" wire:model.live="itemTambahan.{{ $index }}.jumlah"
                                        class="form-input w-full text-sm" min="1" placeholder="1">
                                </div>
                            </div>
                            <div>
                                <label class="form-label text-xs">Nilai (Rp)</label>
                                <input type="number" wire:model.live="itemTambahan.{{ $index }}.harga"
                                    class="form-input w-full text-sm" min="0" placeholder="0">
                            </div>
                        </div>
                        <div class="mt-2 text-right">
                            <span class="text-xs text-gray-500">Subtotal:</span>
                            <span class="text-sm font-semibold text-gray-700 ml-1">Rp
                                {{ number_format(($item['jumlah'] ?? 0) * ($item['harga'] ?? 0), 0, ',', '.') }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-4 p-3 rounded-xl bg-amber-50 border border-amber-100">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-amber-700">Subtotal Item Tambahan</span>
                    <span class="text-lg font-bold text-amber-700">Rp
                        {{ number_format($this->totalItemTambahan, 0, ',', '.') }}</span>
                </div>
            </div>
        @endif
    </div>
</div>
