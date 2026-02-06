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
            <a href="{{ route('admin.kegiatan-ujian.daftar-hadir-panitia', $kegiatanUjian->id) }}"
                class="hover:text-gray-700" wire:navigate>Menu Panitia</a>
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Settings -->
        <div class="card lg:col-span-1 overflow-hidden">
            <div class="p-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                <h3 class="text-lg font-semibold text-gray-900">Pengaturan</h3>
                <p class="text-sm text-gray-500 mt-0.5">Atur jadwal dan jumlah soal</p>
            </div>
            <div class="p-5 space-y-5">
                <div>
                    <label class="form-label">Pilih Jadwal/Mapel</label>
                    <select wire:model.live="selectedJadwalId" class="form-select w-full">
                        <option value="">-- Semua Jadwal --</option>
                        @php
                            $hariIndonesia = [
                                'Sunday' => 'Minggu',
                                'Monday' => 'Senin',
                                'Tuesday' => 'Selasa',
                                'Wednesday' => 'Rabu',
                                'Thursday' => 'Kamis',
                                'Friday' => 'Jumat',
                                'Saturday' => 'Sabtu',
                            ];
                        @endphp
                        @foreach ($jadwalList as $jadwal)
                            <option value="{{ $jadwal->id }}">
                                {{ $hariIndonesia[$jadwal->tanggal->format('l')] }},
                                {{ $jadwal->tanggal->format('d/m/Y') }} -
                                {{ $jadwal->mata_pelajaran }}{{ $jadwal->kelompok_kelas ? ' [' . $jadwal->kelompok_kelas . ']' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Jumlah Soal per Ruang</label>
                    <input type="number" wire:model.live="jumlahSoalPerRuang" class="form-input w-full" min="1">
                </div>
                <div>
                    <label class="form-label">Soal Cadangan</label>
                    <input type="number" wire:model.live="jumlahCadangan" class="form-input w-full" min="0">
                </div>
                <div class="pt-5 border-t border-gray-100">
                    <button onclick="window.print()"
                        class="inline-flex items-center justify-center gap-2 w-full px-4 py-2.5 bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-xl text-sm font-medium text-white hover:from-emerald-600 hover:to-emerald-700 transition-all duration-200 shadow-sm shadow-emerald-500/25">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                            </path>
                        </svg>
                        Cetak Label
                    </button>
                </div>
            </div>
        </div>

        <!-- Preview Labels -->
        <div class="card lg:col-span-2 overflow-hidden" id="print-area">
            <div class="p-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white print:hidden">
                <h3 class="text-lg font-semibold text-gray-900">Preview Label</h3>
                <p class="text-sm text-gray-500 mt-0.5">Label akan dicetak 2 kolom per halaman</p>
            </div>
            <div class="p-5 print-content">
                @php
                    $jadwalsToPrint = $selectedJadwalId ? [$selectedJadwal] : $jadwalList;
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
                    @foreach ($jadwalsToPrint as $jadwal)
                        @if ($jadwal)
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
                                        @if ($jadwal->kelompok_kelas)
                                            <div class="font-semibold text-blue-700">{{ $jadwal->kelompok_kelas }}
                                            </div>
                                        @endif
                                        <div>{{ $hariIndonesiaPrint[$jadwal->tanggal->format('l')] }},
                                            {{ $jadwal->tanggal->format('d/m/Y') }}</div>
                                        <div>Waktu: {{ $jadwal->waktu }}</div>
                                        <div class="border-t border-black pt-2 mt-2">
                                            <span class="font-bold text-xl">RUANG {{ $ruang->kode }}</span>
                                        </div>
                                        <div class="grid grid-cols-2 gap-2 text-xs mt-2">
                                            <div class="border border-gray-400 p-1">
                                                <div class="font-semibold">Jumlah Soal</div>
                                                <div class="text-lg font-bold">{{ $jumlahSoalPerRuang }}</div>
                                            </div>
                                            <div class="border border-gray-400 p-1">
                                                <div class="font-semibold">Cadangan</div>
                                                <div class="text-lg font-bold">{{ $jumlahCadangan }}</div>
                                            </div>
                                        </div>
                                        <div class="text-xs font-semibold mt-2 border-t border-black pt-1">
                                            Total: {{ $jumlahSoalPerRuang + $jumlahCadangan }} lembar
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    @endforeach
                </div>

                @if ($jadwalList->isEmpty())
                    <div class="text-center py-12 text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-300 mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        <p>Belum ada jadwal ujian. Silakan buat jadwal terlebih dahulu.</p>
                    </div>
                @endif

                @if ($ruangList->isEmpty())
                    <div class="text-center py-12 text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-300 mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg>
                        <p>Belum ada ruang ujian. Silakan buat ruang ujian terlebih dahulu.</p>
                    </div>
                @endif
            </div>
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
