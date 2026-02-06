<div>
    <x-slot name="header">Daftar Hadir Peserta</x-slot>

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
            <span class="font-medium text-gray-900">Daftar Hadir</span>
        </nav>
    </div>

    <!-- Header Info -->
    <div class="mb-6 p-4 rounded-xl bg-gray-50 border border-gray-200">
        <h2 class="text-lg font-semibold text-gray-900">{{ $kegiatanUjian->nama_ujian }}</h2>
        <p class="text-sm text-gray-500 mt-1">{{ $kegiatanUjian->tahunAjaran->nama }} -
            {{ $kegiatanUjian->tahunAjaran->semester }}</p>
    </div>

    @if ($selectedRuangId && $daftarHadirData)
        <!-- Daftar Hadir View -->
        <div class="mb-6">
            <button wire:click="clearSelection" class="btn btn-secondary mb-4 print:hidden">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Daftar Ruang
            </button>

            <!-- Printable Area -->
            <div class="bg-white" id="daftar-hadir-print">
                <div class="p-6 border-b border-gray-200 flex items-center justify-between print:hidden">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Daftar Hadir - {{ $daftarHadirData['ruang']['nama'] }}
                    </h3>
                    <button onclick="window.print()" class="btn btn-primary">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                            </path>
                        </svg>
                        Cetak
                    </button>
                </div>

                <div class="p-6">
                    <!-- Kop Surat -->
                    <div class="flex items-center gap-4 border-b-4 border-double border-black pb-3 mb-4">
                        <div class="w-16 h-16 flex-shrink-0">
                            @if (!empty($daftarHadirData['schoolSettings']['logo']))
                                <img src="{{ asset('storage/' . $daftarHadirData['schoolSettings']['logo']) }}"
                                    alt="Logo" class="w-full h-full object-contain">
                            @else
                                <svg viewBox="0 0 24 24" fill="#666" class="w-full h-full">
                                    <path
                                        d="M12 3L1 9l4 2.18v6L12 21l7-3.82v-6l2-1.09V17h2V9L12 3zm6.82 6L12 12.72 5.18 9 12 5.28 18.82 9zM17 15.99l-5 2.73-5-2.73v-3.72L12 15l5-2.73v3.72z" />
                                </svg>
                            @endif
                        </div>
                        <div class="flex-1 text-center">
                            <div class="text-xs font-bold">KEMENTERIAN AGAMA REPUBLIK INDONESIA</div>
                            <div class="text-lg font-bold">
                                {{ strtoupper($daftarHadirData['schoolSettings']['nama_sekolah'] ?? 'NAMA SEKOLAH') }}
                            </div>
                            <div class="text-xs">Alamat: {{ $daftarHadirData['schoolSettings']['alamat'] ?? '' }}
                                {{ $daftarHadirData['schoolSettings']['kelurahan'] ?? '' }}
                                {{ $daftarHadirData['schoolSettings']['kecamatan'] ?? '' }}</div>
                            <div class="text-xs">
                                {{ strtoupper($daftarHadirData['schoolSettings']['kabupaten'] ?? '') }}
                                {{ $daftarHadirData['schoolSettings']['kode_pos'] ?? '' }}</div>
                        </div>
                    </div>

                    <!-- Title -->
                    <div class="text-center mb-4">
                        <h1 class="text-lg font-bold">DAFTAR HADIR PESERTA</h1>
                        <h2 class="text-base font-bold">{{ strtoupper($kegiatanUjian->nama_ujian) }}</h2>
                        <h3 class="text-sm font-bold">TAHUN {{ $kegiatanUjian->tahunAjaran->nama }}</h3>
                    </div>

                    <!-- Info Section -->
                    <div class="mb-4 text-sm">
                        <table class="w-full">
                            <tr>
                                <td class="w-1/2">
                                    <table>
                                        <tr>
                                            <td class="py-1 w-40">KOTA/KABUPATEN</td>
                                            <td class="py-1">:
                                                {{ $daftarHadirData['schoolSettings']['kabupaten'] ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="py-1">SEKOLAH/MADRASAH</td>
                                            <td class="py-1">:
                                                {{ $daftarHadirData['schoolSettings']['nama_sekolah'] ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="py-1">RUANG</td>
                                            <td class="py-1">: {{ $daftarHadirData['ruang']['nama'] }}</td>
                                        </tr>
                                        <tr>
                                            <td class="py-1">HARI</td>
                                            <td class="py-1">: .................................</td>
                                        </tr>
                                    </table>
                                </td>
                                <td class="w-1/2 align-top">
                                    <table>
                                        <tr>
                                            <td class="py-1 w-24">KODE</td>
                                            <td class="py-1">: {{ $daftarHadirData['ruang']['kode'] }}</td>
                                        </tr>
                                        <tr>
                                            <td class="py-1">SESI</td>
                                            <td class="py-1">: ............</td>
                                        </tr>
                                        <tr>
                                            <td class="py-1">TANGGAL</td>
                                            <td class="py-1">: .................................</td>
                                        </tr>
                                        <tr>
                                            <td class="py-1">PUKUL</td>
                                            <td class="py-1">: .............. - ..............</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <!-- Attendance Table -->
                    <table class="w-full border-collapse border border-black text-sm mb-4">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border border-black py-1 px-2 w-10">No.</th>
                                <th class="border border-black py-1 px-2 w-28">No. Peserta</th>
                                <th class="border border-black py-1 px-2">Nama Peserta</th>
                                <th class="border border-black py-1 px-2 w-20">L/P</th>
                                <th class="border border-black py-1 px-2 w-24">Asal Kelas</th>
                                <th class="border border-black py-1 px-2 w-28">Tanda Tangan</th>
                                <th class="border border-black py-1 px-2 w-24">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($daftarHadirData['penempatans'] as $index => $penempatan)
                                <tr>
                                    <td class="border border-black py-1 px-2 text-center">{{ $index + 1 }}</td>
                                    <td class="border border-black py-1 px-2 font-mono text-xs">
                                        {{ $penempatan->nomor_peserta ?? '-' }}</td>
                                    <td class="border border-black py-1 px-2">
                                        {{ $penempatan->siswa->nama_lengkap ?? '-' }}</td>
                                    <td class="border border-black py-1 px-2 text-center">
                                        {{ $penempatan->siswa->jenis_kelamin ?? '-' }}</td>
                                    <td class="border border-black py-1 px-2 text-center">
                                        {{ $penempatan->asal_kelas ?? '-' }}</td>
                                    <td class="border border-black py-1 px-2">{{ $index + 1 }}.</td>
                                    <td class="border border-black py-1 px-2"></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Footer Notes -->
                    <div class="text-xs mb-4">
                        <p class="font-bold mb-1">Keterangan:</p>
                        <ol class="list-decimal list-inside space-y-1">
                            <li>Dibuat rangkap 3 (tiga), masing-masing untuk sekolah, kota/kab dan Provinsi.</li>
                            <li>Pengawas ruang menyilang Nama Peserta yang tidak hadir.</li>
                        </ol>
                    </div>

                    <!-- Summary -->
                    <div class="flex justify-between items-start text-sm">
                        <div>
                            <table>
                                <tr>
                                    <td class="py-1">Jumlah Peserta yang Seharusnya Hadir</td>
                                    <td class="py-1">: <span
                                            class="font-bold">{{ $daftarHadirData['total_siswa'] }}</span> peserta
                                    </td>
                                </tr>
                                <tr>
                                    <td class="py-1">Jumlah Peserta yang Tidak Hadir</td>
                                    <td class="py-1">: .......... peserta</td>
                                </tr>
                                <tr>
                                    <td class="py-1">Jumlah Peserta Hadir</td>
                                    <td class="py-1">: .......... peserta</td>
                                </tr>
                            </table>
                        </div>

                        <!-- Signatures -->
                        <div class="text-center">
                            <table class="mx-auto">
                                <tr>
                                    <td class="px-8 text-center">
                                        <p class="font-bold mb-12">Pengawas I</p>
                                        <p>(.................................)</p>
                                        <p class="text-xs">NIP.</p>
                                    </td>
                                    <td class="px-8 text-center">
                                        <p class="font-bold mb-12">Pengawas II</p>
                                        <p>(.................................)</p>
                                        <p class="text-xs">NIP.</p>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Room List -->
        <div class="card">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Pilih Ruang untuk Melihat Daftar Hadir</h3>
            </div>

            <div class="p-6">
                @if ($ruangWithPenempatan->isEmpty())
                    <div class="text-center py-8 text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-300 mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2-2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                            </path>
                        </svg>
                        <p>Belum ada penempatan siswa</p>
                        <p class="text-sm mt-1">Silakan generate penempatan di modul Acak Kelas atau Penempatan Per
                            Kelas terlebih dahulu</p>
                    </div>
                @else
                    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                        @foreach ($ruangWithPenempatan as $ruang)
                            <div class="rounded-xl border border-gray-200 bg-white p-4 hover:shadow-md transition-shadow cursor-pointer"
                                wire:click="selectRuang({{ $ruang['id'] }})">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="w-12 h-12 rounded-lg bg-amber-100 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                            </path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900">{{ $ruang['kode'] }}</h4>
                                        <p class="text-sm text-gray-500">{{ $ruang['nama'] }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between">
                                    <span class="badge badge-primary">{{ $ruang['terisi'] }} siswa</span>
                                    <span class="text-blue-600 text-sm font-medium">Lihat Daftar Hadir →</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Print Styles -->
    <style>
        @page {
            size: 215mm 330mm;
            /* F4 portrait */
            margin: 5mm 8mm;
        }

        @media print {
            body * {
                visibility: hidden;
            }

            #daftar-hadir-print,
            #daftar-hadir-print * {
                visibility: visible;
            }

            #daftar-hadir-print {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }

            /* Remove gray backgrounds */
            #daftar-hadir-print .bg-gray-100,
            #daftar-hadir-print .bg-gray-50 {
                background-color: white !important;
            }

            #daftar-hadir-print .card {
                box-shadow: none !important;
                border: none !important;
            }

            #daftar-hadir-print .p-6 {
                padding: 4px !important;
            }

            #daftar-hadir-print table td,
            #daftar-hadir-print table th {
                padding: 1.5px 4px !important;
                font-size: 9px !important;
                line-height: 1.2 !important;
            }

            #daftar-hadir-print .text-lg {
                font-size: 12px !important;
            }

            #daftar-hadir-print .text-base {
                font-size: 10px !important;
            }

            #daftar-hadir-print .text-sm {
                font-size: 9px !important;
            }

            #daftar-hadir-print .text-xs {
                font-size: 8px !important;
            }

            #daftar-hadir-print .mb-4 {
                margin-bottom: 4px !important;
            }

            #daftar-hadir-print .mb-12 {
                margin-bottom: 30px !important;
            }

            #daftar-hadir-print .pb-3 {
                padding-bottom: 4px !important;
            }

            #daftar-hadir-print .gap-4 {
                gap: 8px !important;
            }

            #daftar-hadir-print .w-16 {
                width: 40px !important;
                height: 40px !important;
            }

            #daftar-hadir-print .border-b-4 {
                border-bottom-width: 2px !important;
            }

            .print\:hidden {
                display: none !important;
            }
        }
    </style>
</div>
