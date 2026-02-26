<div>
    <x-slot name="header">Label Amplop Soal</x-slot>

    <!-- Breadcrumb -->
    <div class="mb-6">
        <nav class="flex items-center gap-2 text-sm text-gray-500">
            <a href="{{ route('admin.kegiatan-ujian') }}" class="hover:text-gray-700" wire:navigate>Kegiatan Ujian</a>
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            <a href="{{ route('admin.kegiatan-ujian.perangkat', $kegiatanUjian->id) }}" class="hover:text-gray-700"
                wire:navigate>Perangkat</a>
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            <span class="font-medium text-gray-900">Label Amplop Soal</span>
        </nav>
    </div>

    <!-- Header Info -->
    <div
        class="mb-6 p-4 rounded-2xl bg-gradient-to-r from-gray-50 via-white to-gray-50 border border-gray-200/60 shadow-sm">
        <div class="flex items-center gap-3">
            <div
                class="w-10 h-10 rounded-xl bg-gradient-to-br from-cyan-100 to-cyan-200 flex items-center justify-center">
                <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z">
                    </path>
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-gray-900">{{ $kegiatanUjian->nama_ujian }}</h2>
                <p class="text-sm text-gray-500">{{ $kegiatanUjian->tahunAjaran->nama }} ·
                    {{ $kegiatanUjian->tahunAjaran->semester }}</p>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session('message'))
        <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-800">
            {{ session('message') }}
        </div>
    @endif

    <!-- Tabs Kelas -->
    @if ($kelasList->isNotEmpty())
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Kelas</label>
            <div class="flex flex-wrap gap-2">
                @foreach ($kelasList as $kelas)
                    <button wire:click="selectKelas('{{ $kelas->nama }}')"
                        class="px-4 py-2 rounded-xl text-sm font-medium transition-all duration-200
                            {{ $selectedKelas === $kelas->nama
                                ? 'bg-gray-900 text-white shadow-md shadow-gray-900/25'
                                : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50 hover:border-gray-300' }}">
                        {{ $kelas->nama }}
                    </button>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Info Penempatan -->
    @if ($selectedKelas)
        <div class="mb-4">
            @if ($totalSiswa > 0)
                <div
                    class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-blue-50 border border-blue-200 text-sm text-blue-800">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Kelas <strong>{{ $selectedKelas }}</strong>: {{ $totalSiswa }} siswa ditempatkan di
                    {{ $ruangList->count() }} ruang
                    @if ($ruangList->isNotEmpty())
                        ({{ $ruangList->map(fn($r) => 'Ruang ' . $r->kode)->implode(', ') }})
                    @endif
                </div>
            @else
                <div
                    class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-amber-50 border border-amber-200 text-sm text-amber-800">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.072 16.5c-.77.833.192 2.5 1.732 2.5z">
                        </path>
                    </svg>
                    Kelas <strong>{{ $selectedKelas }}</strong> belum ada penempatan ruang.
                </div>
            @endif
        </div>
    @endif

    <!-- Tabel Pelajaran -->
    <div class="card overflow-hidden">
        <div class="p-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
            <h3 class="text-lg font-semibold text-gray-900">Daftar Mata Pelajaran — {{ $selectedKelas ?: '-' }}</h3>
            <p class="text-sm text-gray-500 mt-0.5">Isi jumlah soal dan cadangan per mata pelajaran</p>
        </div>

        <div class="p-0">
            @if ($jadwals->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="table w-full">
                        <thead>
                            <tr>
                                <th class="w-14 text-center">No</th>
                                <th class="w-48">Hari/Tanggal</th>
                                <th>Mata Pelajaran</th>
                                <th class="w-28 text-center">Waktu</th>
                                <th class="w-36 text-center">Jumlah Soal</th>
                                <th class="w-36 text-center">Cadangan</th>
                                <th class="w-28 text-center">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($jadwals as $index => $jadwal)
                                <tr class="hover:bg-gray-50/50">
                                    <td class="text-center text-gray-500">{{ $index + 1 }}</td>
                                    <td class="text-sm">
                                        <div class="font-medium text-gray-900">{{ $jadwal->hari_tanggal }}</div>
                                    </td>
                                    <td>
                                        <div class="font-semibold text-gray-900">{{ $jadwal->mata_pelajaran }}</div>
                                        @if ($jadwal->keterangan)
                                            <div class="text-xs text-gray-500">{{ $jadwal->keterangan }}</div>
                                        @endif
                                    </td>
                                    <td class="text-center text-sm font-mono text-gray-600">{{ $jadwal->waktu }}</td>
                                    <td class="text-center">
                                        <input type="number"
                                            wire:model.lazy="jadwalData.{{ $jadwal->id }}.jumlah_soal"
                                            class="form-input w-24 mx-auto text-center text-sm" min="0"
                                            placeholder="0">
                                    </td>
                                    <td class="text-center">
                                        <input type="number"
                                            wire:model.lazy="jadwalData.{{ $jadwal->id }}.jumlah_cadangan"
                                            class="form-input w-24 mx-auto text-center text-sm" min="0"
                                            placeholder="0">
                                    </td>
                                    <td class="text-center">
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 text-sm font-bold">
                                            {{ (int) ($jadwalData[$jadwal->id]['jumlah_soal'] ?? 0) + (int) ($jadwalData[$jadwal->id]['jumlah_cadangan'] ?? 0) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Action Buttons -->
                <div class="p-4 border-t border-gray-100 bg-gray-50/50 flex items-center justify-end gap-3">
                    <button wire:click="save"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl text-sm font-medium text-white hover:from-blue-600 hover:to-blue-700 transition-all duration-200 shadow-sm shadow-blue-500/25">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        Simpan
                    </button>
                    <button
                        onclick="document.getElementById('print-area').style.display='block'; setTimeout(() => { window.print(); document.getElementById('print-area').style.display='none'; }, 100);"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-xl text-sm font-medium text-white hover:from-emerald-600 hover:to-emerald-700 transition-all duration-200 shadow-sm shadow-emerald-500/25">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                            </path>
                        </svg>
                        Cetak Label
                    </button>
                </div>
            @else
                <div class="text-center py-12 text-gray-500">
                    <svg class="mx-auto h-12 w-12 text-gray-300 mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    <p>Belum ada jadwal ujian untuk kelas ini.</p>
                    <p class="text-sm mt-1">Silakan buat jadwal terlebih dahulu.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Print Area (hidden, shown only during print) -->
    <div id="print-area" style="display: none;">
        @php
            $hariIndonesiaPrint = [
                'Sunday' => 'MINGGU',
                'Monday' => 'SENIN',
                'Tuesday' => 'SELASA',
                'Wednesday' => 'RABU',
                'Thursday' => 'KAMIS',
                'Friday' => 'JUMAT',
                'Saturday' => 'SABTU',
            ];
        @endphp

        <div class="grid grid-cols-2 gap-4 print:gap-2">
            @foreach ($jadwals as $jadwal)
                @if (($jadwalData[$jadwal->id]['jumlah_soal'] ?? 0) > 0)
                    @foreach ($ruangList as $ruang)
                        <div
                            class="border-2 border-black p-4 print:p-3 text-center break-inside-avoid page-break-inside-avoid">
                            <div class="text-xs font-bold mb-1">
                                {{ strtoupper($schoolSettings['nama_sekolah'] ?? 'NAMA SEKOLAH') }}</div>
                            <div class="text-lg font-bold border-b-2 border-black pb-2 mb-2">
                                {{ strtoupper($kegiatanUjian->nama_ujian) }}
                            </div>
                            <div class="space-y-1 text-sm">
                                <div class="font-bold text-lg">{{ $jadwal->mata_pelajaran }}</div>
                                <div class="font-semibold">KELAS {{ $selectedKelas }}</div>
                                <div>{{ $hariIndonesiaPrint[$jadwal->tanggal->format('l')] }},
                                    {{ $jadwal->tanggal->format('d/m/Y') }}</div>
                                <div>Waktu: {{ $jadwal->waktu }}</div>
                                <div class="border-t border-black pt-2 mt-2">
                                    <span class="font-bold text-xl">RUANG {{ $ruang->kode }}</span>
                                </div>
                                <div class="grid grid-cols-2 gap-2 text-xs mt-2">
                                    <div class="border border-gray-400 p-1">
                                        <div class="font-semibold">Jumlah Soal</div>
                                        <div class="text-lg font-bold">
                                            {{ $jadwalData[$jadwal->id]['jumlah_soal'] ?? 0 }}</div>
                                    </div>
                                    <div class="border border-gray-400 p-1">
                                        <div class="font-semibold">Cadangan</div>
                                        <div class="text-lg font-bold">
                                            {{ $jadwalData[$jadwal->id]['jumlah_cadangan'] ?? 0 }}</div>
                                    </div>
                                </div>
                                <div class="text-xs font-semibold mt-2 border-t border-black pt-1">
                                    Total:
                                    {{ ($jadwalData[$jadwal->id]['jumlah_soal'] ?? 0) + ($jadwalData[$jadwal->id]['jumlah_cadangan'] ?? 0) }}
                                    lembar
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            @endforeach
        </div>
    </div>

    <!-- Print Styles -->
    <style>
        @media print {
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            body * {
                visibility: hidden;
            }

            #print-area,
            #print-area * {
                visibility: visible;
            }

            #print-area {
                display: block !important;
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                padding: 5mm;
                box-shadow: none !important;
            }

            @page {
                size: A4 portrait;
                margin: 5mm;
            }

            .card {
                box-shadow: none !important;
                border: none !important;
            }

            .page-break-inside-avoid {
                page-break-inside: avoid;
            }
        }
    </style>
</div>
