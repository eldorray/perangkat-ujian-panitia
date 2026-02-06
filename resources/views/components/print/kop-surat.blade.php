@props(['settings' => []])

<div class="flex items-center gap-4 border-b-4 border-double border-black pb-3 mb-6">
    <div class="w-20 h-20 flex-shrink-0">
        @if (!empty($settings['logo']))
            <img src="{{ asset('storage/' . $settings['logo']) }}" alt="Logo" class="w-full h-full object-contain">
        @else
            <svg viewBox="0 0 24 24" fill="#666" class="w-full h-full">
                <path
                    d="M12 3L1 9l4 2.18v6L12 21l7-3.82v-6l2-1.09V17h2V9L12 3zm6.82 6L12 12.72 5.18 9 12 5.28 18.82 9zM17 15.99l-5 2.73-5-2.73v-3.72L12 15l5-2.73v3.72z" />
            </svg>
        @endif
    </div>
    <div class="flex-1 text-center">
        <div class="text-sm font-bold">KEMENTERIAN AGAMA REPUBLIK INDONESIA</div>
        <div class="text-lg font-bold">{{ strtoupper($settings['nama_sekolah'] ?? 'NAMA SEKOLAH') }}</div>
        <div class="text-xs">
            Alamat: {{ $settings['alamat'] ?? '' }}
            {{ $settings['kelurahan'] ?? '' }}
            {{ $settings['kecamatan'] ?? '' }}
            Telp. {{ $settings['telepon'] ?? '' }}
        </div>
        <div class="text-xs">
            {{ strtoupper($settings['kabupaten'] ?? '') }}
            {{ $settings['kode_pos'] ?? '' }}
        </div>
    </div>
</div>
