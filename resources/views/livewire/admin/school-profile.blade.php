<div>
    <x-slot name="header">Profil Sekolah</x-slot>

    @if (session('success'))
        <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-800">{{ session('success') }}
        </div>
    @endif

    <form wire:submit="save">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Logo Section -->
            <div class="lg:col-span-1">
                <div class="card p-6">
                    <h3 class="text-lg font-semibold mb-4">Logo Sekolah</h3>

                    <div class="flex flex-col items-center">
                        <!-- Current Logo Preview -->
                        <div
                            class="w-40 h-40 rounded-xl border-2 border-dashed border-gray-300 flex items-center justify-center overflow-hidden bg-gray-50 mb-4">
                            @if ($logo)
                                <img src="{{ $logo->temporaryUrl() }}" alt="Preview Logo"
                                    class="w-full h-full object-contain">
                            @elseif($currentLogo)
                                <img src="{{ Storage::url($currentLogo) }}" alt="Logo Sekolah"
                                    class="w-full h-full object-contain">
                            @else
                                <div class="text-center text-gray-400">
                                    <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <span class="text-sm">Belum ada logo</span>
                                </div>
                            @endif
                        </div>

                        <!-- Upload Input -->
                        <div class="w-full">
                            <input type="file" wire:model="logo" accept="image/*" class="input w-full text-sm p-2">
                            <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, GIF. Max: 2MB</p>
                            @error('logo')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        @if ($currentLogo)
                            <button type="button" wire:click="removeLogo" wire:confirm="Yakin ingin menghapus logo?"
                                class="btn btn-ghost text-red-600 mt-3">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                    </path>
                                </svg>
                                Hapus Logo
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Info Section -->
            <div class="lg:col-span-2">
                <div class="card p-6 space-y-6">
                    <h3 class="text-lg font-semibold">Informasi Sekolah</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium mb-1">Nama Sekolah <span
                                    class="text-red-500">*</span></label>
                            <input type="text" wire:model="nama_sekolah" class="input w-full"
                                placeholder="SMA Negeri 1 Contoh">
                            @error('nama_sekolah')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">NPSN <span
                                    class="text-red-500">*</span></label>
                            <input type="text" wire:model="npsn" class="input w-full" placeholder="12345678">
                            @error('npsn')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Telepon</label>
                            <input type="text" wire:model="telepon" class="input w-full" placeholder="(021) 1234567">
                            @error('telepon')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Email</label>
                            <input type="email" wire:model="email" class="input w-full"
                                placeholder="info@sekolah.sch.id">
                            @error('email')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Website</label>
                            <input type="url" wire:model="website" class="input w-full"
                                placeholder="https://sekolah.sch.id">
                            @error('website')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <hr class="border-gray-200">

                    <h3 class="text-lg font-semibold">Alamat</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium mb-1">Alamat Lengkap <span
                                    class="text-red-500">*</span></label>
                            <textarea wire:model="alamat" class="input w-full" rows="2" placeholder="Jl. Pendidikan No. 1"></textarea>
                            @error('alamat')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Kelurahan/Desa</label>
                            <input type="text" wire:model="kelurahan" class="input w-full" placeholder="Kelurahan">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Kecamatan</label>
                            <input type="text" wire:model="kecamatan" class="input w-full" placeholder="Kecamatan">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Kabupaten/Kota</label>
                            <input type="text" wire:model="kabupaten" class="input w-full"
                                placeholder="Kabupaten">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Provinsi</label>
                            <input type="text" wire:model="provinsi" class="input w-full" placeholder="Provinsi">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Kode Pos</label>
                            <input type="text" wire:model="kode_pos" class="input w-full" placeholder="12345">
                        </div>
                    </div>

                    <hr class="border-gray-200">

                    <h3 class="text-lg font-semibold">Kepala Sekolah</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Nama Kepala Sekolah</label>
                            <input type="text" wire:model="kepala_sekolah" class="input w-full"
                                placeholder="Dr. Budi Santoso, M.Pd">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">NIP</label>
                            <input type="text" wire:model="nip_kepala_sekolah" class="input w-full"
                                placeholder="197001011990031001">
                        </div>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="save">Simpan Perubahan</span>
                            <span wire:loading wire:target="save">Menyimpan...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
