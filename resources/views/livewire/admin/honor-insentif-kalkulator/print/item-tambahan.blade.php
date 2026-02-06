{{-- Item Tambahan Print Page --}}
@props(['kegiatanUjian', 'schoolSettings', 'itemTambahan', 'totalItemTambahan', 'terbilang'])

<div class="print-page p-8">
    <x-print.kop-surat :settings="$schoolSettings" />

    <div class="text-center mb-6">
        <h2 class="text-lg font-bold border-b-2 border-black inline-block px-6 pb-1">DAFTAR HONOR/INSENTIF TAMBAHAN</h2>
        <p class="text-sm mt-3 font-semibold">{{ strtoupper($kegiatanUjian->nama_ujian) }}</p>
        <p class="text-sm">Tahun Ajaran {{ $kegiatanUjian->tahunAjaran->nama }}</p>
    </div>

    <table class="w-full text-sm border-collapse border border-black mb-4">
        <thead>
            <tr class="bg-gray-100">
                <th class="border border-black px-2 py-1.5 text-center w-8">No</th>
                <th class="border border-black px-2 py-1.5 text-left">Uraian</th>
                <th class="border border-black px-2 py-1.5 text-center w-12">Jml</th>
                <th class="border border-black px-2 py-1.5 text-right w-24">Harga</th>
                <th class="border border-black px-2 py-1.5 text-right w-28">Total (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @php $itemNo = 1; @endphp
            @foreach ($itemTambahan as $item)
                @if (!empty($item['nama']) && ($item['jumlah'] ?? 0) * ($item['harga'] ?? 0) > 0)
                    <tr>
                        <td class="border border-black px-2 py-1.5 text-center">{{ $itemNo++ }}</td>
                        <td class="border border-black px-2 py-1.5">{{ $item['nama'] }}</td>
                        <td class="border border-black px-2 py-1.5 text-center">{{ $item['jumlah'] }}</td>
                        <td class="border border-black px-2 py-1.5 text-right">
                            {{ number_format($item['harga'], 0, ',', '.') }}</td>
                        <td class="border border-black px-2 py-1.5 text-right">
                            {{ number_format(($item['jumlah'] ?? 0) * ($item['harga'] ?? 0), 0, ',', '.') }}
                        </td>
                    </tr>
                @endif
            @endforeach
            <tr class="bg-gray-100 font-semibold">
                <td colspan="4" class="border border-black px-2 py-1.5 text-right">TOTAL</td>
                <td class="border border-black px-2 py-1.5 text-right">
                    {{ number_format($totalItemTambahan, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <x-print.terbilang :terbilang="$terbilang" />
    <x-print.signature :settings="$schoolSettings" />
</div>
