{{-- Daftar Pengawas Section --}}
<div class="card overflow-hidden">
    <div class="p-4 border-b border-gray-100 bg-gradient-to-r from-emerald-50 to-white">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                        </path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-semibold text-gray-900">Daftar Pengawas</h3>
                    <p class="text-xs text-gray-500">Honor = Jumlah Kehadiran Mengawas × Tarif per Kehadiran</p>
                </div>
            </div>
            <button wire:click="addPengawas"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-100 text-emerald-700 rounded-lg text-sm font-medium hover:bg-emerald-200 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Pengawas
            </button>
        </div>
    </div>
    <div class="p-5">
        @if (count($daftarPengawas) === 0)
            <div class="text-center py-8 text-gray-400">
                <svg class="mx-auto h-10 w-10 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                    </path>
                </svg>
                <p class="text-sm">Belum ada pengawas</p>
                <p class="text-xs mt-1">Klik tombol "Tambah Pengawas" untuk menambahkan</p>
            </div>
        @else
            <div class="space-y-3">
                @foreach ($daftarPengawas as $index => $pengawas)
                    <div class="p-4 rounded-xl bg-gray-50 border border-gray-100 relative group">
                        <button wire:click="removePengawas({{ $index }})"
                            class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center hover:bg-red-600">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
                            <div class="md:col-span-6">
                                <label class="form-label text-xs">Nama Guru</label>
                                <x-form.searchable-select :options="$this->guruOptions" :selected="$pengawas['guru_id'] ?? null"
                                    placeholder="-- Pilih Guru --" search-placeholder="Cari guru..." color="emerald"
                                    x-on:select="$wire.set('daftarPengawas.{{ $index }}.guru_id', $event.detail.value); $wire.call('updatePengawasNama', {{ $index }}, $event.detail.value)" />
                            </div>
                            <div class="md:col-span-3">
                                <label class="form-label text-xs">Jml Hadir</label>
                                <input type="number" wire:model.live="daftarPengawas.{{ $index }}.jumlah_hadir"
                                    class="form-input w-full text-sm text-center" min="1" placeholder="1">
                            </div>
                            <div class="md:col-span-3">
                                <label class="form-label text-xs">Honor</label>
                                <div
                                    class="px-3 py-2.5 bg-emerald-50 rounded-xl text-sm font-semibold text-emerald-700 text-right">
                                    Rp
                                    {{ number_format(($pengawas['jumlah_hadir'] ?? 0) * $honorPerHadirPengawas, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-4 p-3 rounded-xl bg-emerald-50 border border-emerald-100">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-sm text-emerald-700">Subtotal Pengawas</span>
                        <span class="text-xs text-emerald-500 ml-2">({{ count($daftarPengawas) }} orang,
                            {{ $this->totalKehadiranPengawas }} kehadiran)</span>
                    </div>
                    <span class="text-lg font-bold text-emerald-700">Rp
                        {{ number_format($this->totalHonorPengawas, 0, ',', '.') }}</span>
                </div>
            </div>
        @endif
    </div>
</div>
