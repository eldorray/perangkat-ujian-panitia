<div>
    <x-slot name="header">Berita Acara Ujian</x-slot>

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
            <span class="font-medium text-gray-900">Berita Acara Ujian</span>
        </nav>
    </div>

    <!-- Header Info -->
    <div
        class="mb-6 p-4 rounded-2xl bg-gradient-to-r from-gray-50 via-white to-gray-50 border border-gray-200/60 shadow-sm">
        <div class="flex items-center gap-3">
            <div
                class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-100 to-amber-200 flex items-center justify-center">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
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

    @if (!$showPreview)
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Form Pilih Jadwal & Ruang -->
            <div class="card overflow-hidden">
                <div class="p-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                    <h3 class="text-lg font-semibold text-gray-900">Pilih Jadwal & Ruang</h3>
                    <p class="text-sm text-gray-500 mt-0.5">Tentukan sesi ujian yang akan dibuat berita acaranya</p>
                </div>
                <div class="p-5 space-y-5">
                    <div>
                        <label class="form-label">Jadwal Ujian</label>
                        <select wire:model.live="selectedJadwalId" class="form-select w-full">
                            <option value="">-- Pilih Jadwal --</option>
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
                                    {{ $jadwal->mata_pelajaran }}
                                    ({{ $jadwal->waktu }}){{ $jadwal->kelompok_kelas ? ' [' . $jadwal->kelompok_kelas . ']' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Ruang Ujian</label>
                        <select wire:model.live="selectedRuangId" class="form-select w-full">
                            <option value="">-- Pilih Ruang --</option>
                            @foreach ($ruangList as $ruang)
                                <option value="{{ $ruang->id }}">{{ $ruang->kode }} - {{ $ruang->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Form Isi Berita Acara -->
            <div class="card overflow-hidden">
                <div class="p-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                    <h3 class="text-lg font-semibold text-gray-900">Data Berita Acara</h3>
                    <p class="text-sm text-gray-500 mt-0.5">Lengkapi data pelaksanaan ujian</p>
                </div>
                <div class="p-5 space-y-5">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Jumlah Hadir</label>
                            <input type="number" wire:model="jumlahHadir" class="form-input w-full" min="0">
                        </div>
                        <div>
                            <label class="form-label">Jumlah Tidak Hadir</label>
                            <input type="number" wire:model="jumlahTidakHadir" class="form-input w-full"
                                min="0">
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Keterangan Pelaksanaan</label>
                        <textarea wire:model="keterangan" class="form-textarea w-full" rows="2"
                            placeholder="Contoh: Ujian berjalan lancar dan tertib"></textarea>
                    </div>
                    <div>
                        <label class="form-label">Kendala (jika ada)</label>
                        <textarea wire:model="kendala" class="form-textarea w-full" rows="2"
                            placeholder="Contoh: Tidak ada kendala / Ada siswa sakit"></textarea>
                    </div>
                    <div class="border-t border-gray-100 pt-5 mt-5">
                        <h4 class="font-medium text-gray-800 mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Pengawas Ruang
                        </h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="form-label">Nama Pengawas 1</label>
                                <input type="text" wire:model="namaPengawas1" class="form-input w-full">
                            </div>
                            <div>
                                <label class="form-label">NIP Pengawas 1</label>
                                <input type="text" wire:model="nipPengawas1" class="form-input w-full">
                            </div>
                            <div>
                                <label class="form-label">Nama Pengawas 2</label>
                                <input type="text" wire:model="namaPengawas2" class="form-input w-full">
                            </div>
                            <div>
                                <label class="form-label">NIP Pengawas 2</label>
                                <input type="text" wire:model="nipPengawas2" class="form-input w-full">
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-3 pt-5">
                        <button wire:click="saveData"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4">
                                </path>
                            </svg>
                            Simpan Data
                        </button>
                        <button wire:click="togglePreview"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl text-sm font-medium text-white hover:from-blue-600 hover:to-blue-700 transition-all duration-200 shadow-sm shadow-blue-500/25">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                </path>
                            </svg>
                            Preview & Cetak
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Preview & Print -->
        <div class="mb-4 flex items-center gap-3">
            <button wire:click="togglePreview"
                class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </button>
            <button onclick="window.print()"
                class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-xl text-sm font-medium text-white hover:from-emerald-600 hover:to-emerald-700 transition-all duration-200 shadow-sm shadow-emerald-500/25">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                    </path>
                </svg>
                Cetak PDF
            </button>
        </div>

        <div class="card overflow-hidden" id="print-area">
            <div class="p-8 print-content">
                @php
                    $hariIndonesiaPrint = [
                        'Sunday' => 'Minggu',
                        'Monday' => 'Senin',
                        'Tuesday' => 'Selasa',
                        'Wednesday' => 'Rabu',
                        'Thursday' => 'Kamis',
                        'Friday' => 'Jumat',
                        'Saturday' => 'Sabtu',
                    ];
                @endphp

                <!-- Kop Surat -->
                <div class="flex items-center gap-4 border-b-4 border-double border-black pb-3 mb-6">
                    <div class="w-20 h-20 flex-shrink-0">
                        @if (!empty($schoolSettings['logo']))
                            <img src="{{ asset('storage/' . $schoolSettings['logo']) }}" alt="Logo"
                                class="w-full h-full object-contain">
                        @else
                            <svg viewBox="0 0 24 24" fill="#666" class="w-full h-full">
                                <path
                                    d="M12 3L1 9l4 2.18v6L12 21l7-3.82v-6l2-1.09V17h2V9L12 3zm6.82 6L12 12.72 5.18 9 12 5.28 18.82 9zM17 15.99l-5 2.73-5-2.73v-3.72L12 15l5-2.73v3.72z" />
                            </svg>
                        @endif
                    </div>
                    <div class="flex-1 text-center">
                        <div class="text-sm font-bold">KEMENTERIAN AGAMA REPUBLIK INDONESIA</div>
                        <div class="text-lg font-bold">
                            {{ strtoupper($schoolSettings['nama_sekolah'] ?? 'NAMA SEKOLAH') }}</div>
                        <div class="text-xs">Alamat: {{ $schoolSettings['alamat'] ?? '' }}
                            {{ $schoolSettings['kelurahan'] ?? '' }} {{ $schoolSettings['kecamatan'] ?? '' }}
                            Telp. {{ $schoolSettings['telepon'] ?? '' }}</div>
                        <div class="text-xs">{{ strtoupper($schoolSettings['kabupaten'] ?? '') }}
                            {{ $schoolSettings['kode_pos'] ?? '' }}</div>
                    </div>
                </div>

                <!-- Title -->
                <div class="text-center mb-6">
                    <h2 class="text-xl font-bold underline">BERITA ACARA PELAKSANAAN UJIAN</h2>
                    <p class="text-sm mt-2">{{ $kegiatanUjian->nama_ujian }}</p>
                    <p class="text-sm">Tahun Ajaran {{ $kegiatanUjian->tahunAjaran->nama }}</p>
                </div>

                <!-- Content -->
                <div class="text-sm leading-relaxed space-y-4">
                    <p>Pada hari ini, <strong>{{ $hariIndonesiaPrint[$selectedJadwal->tanggal->format('l')] }}</strong>
                        tanggal
                        <strong>{{ $selectedJadwal->tanggal->translatedFormat('d F Y') }}</strong>, telah dilaksanakan
                        {{ $kegiatanUjian->nama_ujian }} dengan rincian sebagai berikut:
                    </p>

                    <table class="w-full">
                        <tr>
                            <td class="py-1 w-48">Mata Pelajaran</td>
                            <td class="py-1 w-4">:</td>
                            <td class="py-1 font-semibold">{{ $selectedJadwal->mata_pelajaran }}</td>
                        </tr>
                        <tr>
                            <td class="py-1">Waktu Pelaksanaan</td>
                            <td class="py-1">:</td>
                            <td class="py-1 font-semibold">{{ $selectedJadwal->waktu }} WIB</td>
                        </tr>
                        <tr>
                            <td class="py-1">Ruang Ujian</td>
                            <td class="py-1">:</td>
                            <td class="py-1 font-semibold">{{ $selectedRuang->kode }} - {{ $selectedRuang->nama }}
                            </td>
                        </tr>
                        <tr>
                            <td class="py-1">Jumlah Peserta Hadir</td>
                            <td class="py-1">:</td>
                            <td class="py-1 font-semibold">{{ $jumlahHadir }} orang</td>
                        </tr>
                        <tr>
                            <td class="py-1">Jumlah Peserta Tidak Hadir</td>
                            <td class="py-1">:</td>
                            <td class="py-1 font-semibold">{{ $jumlahTidakHadir }} orang</td>
                        </tr>
                        <tr>
                            <td class="py-1 align-top">Keterangan Pelaksanaan</td>
                            <td class="py-1 align-top">:</td>
                            <td class="py-1">{{ $keterangan ?: 'Ujian berjalan dengan lancar dan tertib.' }}</td>
                        </tr>
                        @if ($kendala)
                            <tr>
                                <td class="py-1 align-top">Kendala</td>
                                <td class="py-1 align-top">:</td>
                                <td class="py-1">{{ $kendala }}</td>
                            </tr>
                        @endif
                    </table>

                    <p class="mt-4">Demikian berita acara ini dibuat dengan sebenarnya untuk dapat dipergunakan
                        sebagaimana mestinya.</p>
                </div>

                <!-- Signatures -->
                <div class="mt-10 grid grid-cols-2 gap-8 text-sm">
                    <div class="text-center">
                        <p>Pengawas 1,</p>
                        <div class="h-20"></div>
                        <p class="font-semibold border-b border-black inline-block px-4">
                            {{ $namaPengawas1 ?: '________________________' }}</p>
                        <p class="text-xs mt-1">NIP. {{ $nipPengawas1 ?: '________________________' }}</p>
                    </div>
                    <div class="text-center">
                        <p>Pengawas 2,</p>
                        <div class="h-20"></div>
                        <p class="font-semibold border-b border-black inline-block px-4">
                            {{ $namaPengawas2 ?: '________________________' }}</p>
                        <p class="text-xs mt-1">NIP. {{ $nipPengawas2 ?: '________________________' }}</p>
                    </div>
                </div>

                <div class="mt-8 text-center text-sm">
                    <p>Mengetahui,</p>
                    <p>Ketua Panitia {{ $kegiatanUjian->nama_ujian }}</p>
                    <div class="h-20"></div>
                    <p class="font-semibold border-b border-black inline-block px-4">________________________</p>
                    <p class="text-xs mt-1">NIP. ________________________</p>
                </div>
            </div>
        </div>
    @endif

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
                padding: 15mm;
                box-shadow: none !important;
                border: none !important;
            }

            @page {
                size: A4 portrait;
                margin: 10mm;
            }

            .card {
                box-shadow: none !important;
                border: none !important;
            }
        }
    </style>
</div>
