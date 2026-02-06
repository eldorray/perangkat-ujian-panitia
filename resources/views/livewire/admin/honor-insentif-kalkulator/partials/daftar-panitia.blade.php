{{-- Daftar Panitia Section --}}
<div class="card overflow-hidden">
    <div class="p-4 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-white">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-semibold text-gray-900">Daftar Panitia</h3>
                    <p class="text-xs text-gray-500">Honor = Jumlah Kehadiran × Tarif per Kehadiran</p>
                </div>
            </div>
            <button wire:click="addPanitia"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg text-sm font-medium hover:bg-blue-200 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Panitia
            </button>
        </div>
    </div>
    <div class="p-5">
        @if (count($daftarPanitia) === 0)
            <div class="text-center py-8 text-gray-400">
                <svg class="mx-auto h-10 w-10 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                    </path>
                </svg>
                <p class="text-sm">Belum ada panitia</p>
                <p class="text-xs mt-1">Klik tombol "Tambah Panitia" untuk menambahkan</p>
            </div>
        @else
            <div class="space-y-3">
                @foreach ($daftarPanitia as $index => $panitia)
                    <div class="p-4 rounded-xl bg-gray-50 border border-gray-100 relative group">
                        <button wire:click="removePanitia({{ $index }})"
                            class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center hover:bg-red-600">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
                            <div class="md:col-span-4">
                                <label class="form-label text-xs">Nama Guru</label>
                                <x-form.searchable-select :options="$this->guruOptions" :selected="$panitia['guru_id'] ?? null"
                                    placeholder="-- Pilih Guru --" search-placeholder="Cari guru..." color="blue"
                                    x-on:select="$wire.set('daftarPanitia.{{ $index }}.guru_id', $event.detail.value); $wire.call('updatePanitiaNama', {{ $index }}, $event.detail.value)" />
                            </div>
                            <div class="md:col-span-3">
                                <label class="form-label text-xs">Jabatan</label>
                                <input type="text" wire:model.blur="daftarPanitia.{{ $index }}.jabatan"
                                    class="form-input w-full text-sm" placeholder="Contoh: Ketua">
                            </div>
                            <div class="md:col-span-2">
                                <label class="form-label text-xs">Jml Hadir</label>
                                <input type="number" wire:model.live="daftarPanitia.{{ $index }}.jumlah_hadir"
                                    class="form-input w-full text-sm text-center" min="1" placeholder="1">
                            </div>
                            <div class="md:col-span-3">
                                <label class="form-label text-xs">Honor</label>
                                <div
                                    class="px-3 py-2.5 bg-blue-50 rounded-xl text-sm font-semibold text-blue-700 text-right">
                                    Rp
                                    {{ number_format(($panitia['jumlah_hadir'] ?? 0) * $honorPerHadirPanitia, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-4 p-3 rounded-xl bg-blue-50 border border-blue-100">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-sm text-blue-700">Subtotal Panitia</span>
                        <span class="text-xs text-blue-500 ml-2">({{ count($daftarPanitia) }} orang,
                            {{ $this->totalKehadiranPanitia }} kehadiran)</span>
                    </div>
                    <span class="text-lg font-bold text-blue-700">Rp
                        {{ number_format($this->totalHonorPanitia, 0, ',', '.') }}</span>
                </div>
            </div>
        @endif
    </div>
</div>
