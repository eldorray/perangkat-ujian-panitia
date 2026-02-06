{{-- Honor Panitia Print Page --}}
@props([
    'kegiatanUjian',
    'schoolSettings',
    'daftarPanitia',
    'honorPerHadirPanitia',
    'totalKehadiranPanitia',
    'totalHonorPanitia',
    'terbilang',
])

<div class="print-page p-8">
    <x-print.kop-surat :settings="$schoolSettings" />

    <div class="text-center mb-6">
        <h2 class="text-lg font-bold border-b-2 border-black inline-block px-6 pb-1">DAFTAR HONOR PANITIA</h2>
        <p class="text-sm mt-3 font-semibold">{{ strtoupper($kegiatanUjian->nama_ujian) }}</p>
        <p class="text-sm">Tahun Ajaran {{ $kegiatanUjian->tahunAjaran->nama }}</p>
    </div>

    <p class="text-xs mb-2">Tarif: Rp {{ number_format($honorPerHadirPanitia, 0, ',', '.') }} per kehadiran</p>

    <table class="w-full text-sm border-collapse border border-black mb-4">
        <thead>
            <tr class="bg-gray-100">
                <th class="border border-black px-2 py-1.5 text-center w-8">No</th>
                <th class="border border-black px-2 py-1.5 text-left">Nama</th>
                <th class="border border-black px-2 py-1.5 text-left w-24">Jabatan</th>
                <th class="border border-black px-2 py-1.5 text-center w-12">Hadir</th>
                <th class="border border-black px-2 py-1.5 text-right w-24">Honor (Rp)</th>
                <th class="border border-black px-2 py-1.5 text-center w-28">Tanda Tangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($daftarPanitia as $index => $panitia)
                @if (!empty($panitia['nama']) || !empty($panitia['guru_id']))
                    <tr>
                        <td class="border border-black px-2 py-1.5 text-center">{{ $index + 1 }}</td>
                        <td class="border border-black px-2 py-1.5">{{ $panitia['nama'] ?? '-' }}</td>
                        <td class="border border-black px-2 py-1.5">{{ $panitia['jabatan'] ?? '-' }}</td>
                        <td class="border border-black px-2 py-1.5 text-center">{{ $panitia['jumlah_hadir'] ?? 0 }}</td>
                        <td class="border border-black px-2 py-1.5 text-right">
                            {{ number_format(($panitia['jumlah_hadir'] ?? 0) * $honorPerHadirPanitia, 0, ',', '.') }}
                        </td>
                        <td class="border border-black px-2 py-6"></td>
                    </tr>
                @endif
            @endforeach
            <tr class="bg-gray-100 font-semibold">
                <td colspan="3" class="border border-black px-2 py-1.5 text-right">TOTAL</td>
                <td class="border border-black px-2 py-1.5 text-center">{{ $totalKehadiranPanitia }}</td>
                <td class="border border-black px-2 py-1.5 text-right">
                    {{ number_format($totalHonorPanitia, 0, ',', '.') }}</td>
                <td class="border border-black px-2 py-1.5"></td>
            </tr>
        </tbody>
    </table>

    <x-print.terbilang :terbilang="$terbilang" />
    <x-print.signature :settings="$schoolSettings" />
</div>
