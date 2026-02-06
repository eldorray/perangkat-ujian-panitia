<div>
    <x-slot name="header">Pengaturan Aplikasi</x-slot>

    @if (session('success'))
        <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="card p-6">
        <form wire:submit="save" class="space-y-8">
            <!-- App Name -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Nama Aplikasi</h3>
                <div>
                    <label class="block text-sm font-medium mb-1">Nama Aplikasi</label>
                    <input type="text" wire:model="appName" class="input w-full max-w-md" placeholder="Nama aplikasi">
                    @error('appName')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- App Logo -->
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Logo Aplikasi</h3>
                <p class="text-sm text-gray-500 mb-4">Logo akan ditampilkan di sidebar dan header aplikasi. Disarankan
                    menggunakan gambar dengan rasio 1:1 atau format SVG.</p>

                <div class="flex items-start gap-6">
                    <!-- Current Logo Preview -->
                    <div class="flex-shrink-0">
                        <div
                            class="w-24 h-24 rounded-xl border-2 border-dashed border-gray-300 flex items-center justify-center bg-gray-50 overflow-hidden">
                            @if ($appLogo)
                                <img src="{{ $appLogo->temporaryUrl() }}" alt="Preview Logo"
                                    class="w-full h-full object-contain">
                            @elseif ($currentAppLogo)
                                <img src="{{ Storage::url($currentAppLogo) }}" alt="Logo Aplikasi"
                                    class="w-full h-full object-contain">
                            @else
                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            @endif
                        </div>
                    </div>

                    <!-- Upload Section -->
                    <div class="flex-1">
                        <label class="block">
                            <span class="sr-only">Pilih logo</span>
                            <input type="file" wire:model="appLogo"
                                accept="image/png,image/jpg,image/jpeg,image/svg+xml"
                                class="block w-full text-sm text-gray-500
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-lg file:border-0
                                    file:text-sm file:font-medium
                                    file:bg-gray-900 file:text-white
                                    hover:file:bg-gray-800
                                    cursor-pointer">
                        </label>
                        <p class="mt-2 text-xs text-gray-500">PNG, JPG, JPEG, atau SVG. Maksimal 2MB.</p>
                        @error('appLogo')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror

                        @if ($currentAppLogo)
                            <button type="button" wire:click="removeAppLogo"
                                wire:confirm="Apakah Anda yakin ingin menghapus logo?"
                                class="mt-3 text-sm text-red-600 hover:text-red-800 flex items-center gap-1">
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

            <!-- Favicon -->
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Favicon</h3>
                <p class="text-sm text-gray-500 mb-4">Favicon adalah ikon kecil yang muncul di tab browser. Disarankan
                    menggunakan format PNG atau ICO dengan ukuran 32x32 atau 64x64 pixel.</p>

                <div class="flex items-start gap-6">
                    <!-- Current Favicon Preview -->
                    <div class="flex-shrink-0">
                        <div
                            class="w-16 h-16 rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center bg-gray-50 overflow-hidden">
                            @if ($favicon)
                                <img src="{{ $favicon->temporaryUrl() }}" alt="Preview Favicon"
                                    class="w-full h-full object-contain">
                            @elseif ($currentFavicon)
                                <img src="{{ Storage::url($currentFavicon) }}" alt="Favicon"
                                    class="w-full h-full object-contain">
                            @else
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z">
                                    </path>
                                </svg>
                            @endif
                        </div>
                    </div>

                    <!-- Upload Section -->
                    <div class="flex-1">
                        <label class="block">
                            <span class="sr-only">Pilih favicon</span>
                            <input type="file" wire:model="favicon"
                                accept="image/png,image/x-icon,image/vnd.microsoft.icon"
                                class="block w-full text-sm text-gray-500
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-lg file:border-0
                                    file:text-sm file:font-medium
                                    file:bg-gray-900 file:text-white
                                    hover:file:bg-gray-800
                                    cursor-pointer">
                        </label>
                        <p class="mt-2 text-xs text-gray-500">ICO atau PNG. Maksimal 512KB. Ukuran disarankan 32x32 atau
                            64x64 pixel.</p>
                        @error('favicon')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror

                        @if ($currentFavicon)
                            <button type="button" wire:click="removeFavicon"
                                wire:confirm="Apakah Anda yakin ingin menghapus favicon?"
                                class="mt-3 text-sm text-red-600 hover:text-red-800 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                    </path>
                                </svg>
                                Hapus Favicon
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Save Button -->
            <div class="border-t border-gray-200 pt-6">
                <button type="submit" class="btn btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
</div>
