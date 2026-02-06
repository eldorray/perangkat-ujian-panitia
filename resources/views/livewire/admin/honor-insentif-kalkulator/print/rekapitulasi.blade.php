{{-- Rekapitulasi Print Page --}}
@props([
    'kegiatanUjian',
    'schoolSettings',
    'daftarPanitia',
    'daftarPengawas',
    'totalKehadiranPanitia',
    'totalKehadiranPengawas',
    'totalHonorPanitia',
    'totalHonorPengawas',
    'totalItemTambahan',
    'grandTotal',
    'terbilang',
])

<div class="print-page p-8">
    <x-print.kop-surat :settings="$schoolSettings" />

    <div class="text-center mb-6">
        <h2 class="text-lg font-bold border-b-2 border-black inline-block px-6 pb-1">REKAPITULASI HONOR/INSENTIF</h2>
        <p class="text-sm mt-3 font-semibold">{{ strtoupper($kegiatanUjian->nama_ujian) }}</p>
        <p class="text-sm">Tahun Ajaran {{ $kegiatanUjian->tahunAjaran->nama }}</p>
    </div>

    <table class="w-full text-sm border-collapse border border-black mb-6">
        <thead>
            <tr class="bg-gray-100">
                <th class="border border-black px-3 py-2 text-center w-10">No</th>
                <th class="border border-black px-3 py-2 text-left">Uraian</th>
                <th class="border border-black px-3 py-2 text-center w-16">Jml Orang</th>
                <th class="border border-black px-3 py-2 text-center w-16">Jml Hadir</th>
                <th class="border border-black px-3 py-2 text-right w-36">Jumlah (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @php $rekapNo = 1; @endphp
            @if ($totalHonorPanitia > 0)
                <tr>
                    <td class="border border-black px-3 py-2 text-center">{{ $rekapNo++ }}</td>
                    <td class="border border-black px-3 py-2">Honor Panitia</td>
                    <td class="border border-black px-3 py-2 text-center">{{ count($daftarPanitia) }}</td>
                    <td class="border border-black px-3 py-2 text-center">{{ $totalKehadiranPanitia }}</td>
                    <td class="border border-black px-3 py-2 text-right">
                        {{ number_format($totalHonorPanitia, 0, ',', '.') }}</td>
                </tr>
            @endif
            @if ($totalHonorPengawas > 0)
                <tr>
                    <td class="border border-black px-3 py-2 text-center">{{ $rekapNo++ }}</td>
                    <td class="border border-black px-3 py-2">Honor Pengawas Ruang</td>
                    <td class="border border-black px-3 py-2 text-center">{{ count($daftarPengawas) }}</td>
                    <td class="border border-black px-3 py-2 text-center">{{ $totalKehadiranPengawas }}</td>
                    <td class="border border-black px-3 py-2 text-right">
                        {{ number_format($totalHonorPengawas, 0, ',', '.') }}</td>
                </tr>
            @endif
            @if ($totalItemTambahan > 0)
                <tr>
                    <td class="border border-black px-3 py-2 text-center">{{ $rekapNo++ }}</td>
                    <td class="border border-black px-3 py-2">Item Tambahan</td>
                    <td class="border border-black px-3 py-2 text-center">-</td>
                    <td class="border border-black px-3 py-2 text-center">-</td>
                    <td class="border border-black px-3 py-2 text-right">
                        {{ number_format($totalItemTambahan, 0, ',', '.') }}</td>
                </tr>
            @endif
            <tr class="bg-gray-100 font-bold">
                <td colspan="4" class="border border-black px-3 py-2 text-right">GRAND TOTAL</td>
                <td class="border border-black px-3 py-2 text-right">Rp {{ number_format($grandTotal, 0, ',', '.') }}
                </td>
            </tr>
        </tbody>
    </table>

    <div class="mb-8 p-3 bg-gray-50 border border-gray-200 rounded">
        <p class="text-sm"><span class="font-medium">Terbilang:</span> <em>{{ $terbilang }} rupiah</em></p>
    </div>

    <div class="flex justify-end mt-8">
        <div class="text-center text-sm">
            <p>{{ $schoolSettings['kabupaten'] ?? '' }}, .........................</p>
            <p class="font-bold mt-1">Kepala {{ $schoolSettings['nama_sekolah'] ?? 'Sekolah' }}</p>
            <div class="h-20"></div>
            <p class="font-semibold border-b border-black inline-block px-4">
                {{ $schoolSettings['nama_kepala_sekolah'] ?? '________________________' }}
            </p>
            <p class="text-xs mt-1">NIP. {{ $schoolSettings['nip_kepala_sekolah'] ?? '________________________' }}</p>
        </div>
    </div>
</div>
