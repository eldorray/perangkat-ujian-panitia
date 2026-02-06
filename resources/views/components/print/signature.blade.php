@props(['settings' => []])

<div class="flex justify-end mt-6">
    <div class="text-center text-sm">
        <p>{{ $settings['kabupaten'] ?? '' }}, .........................</p>
        <p class="font-bold mt-1">Kepala {{ $settings['nama_sekolah'] ?? 'Sekolah' }}</p>
        <div class="h-16"></div>
        <p class="font-semibold border-b border-black inline-block px-4">
            {{ $settings['nama_kepala_sekolah'] ?? '________________________' }}
        </p>
        <p class="text-xs mt-1">NIP. {{ $settings['nip_kepala_sekolah'] ?? '________________________' }}</p>
    </div>
</div>
