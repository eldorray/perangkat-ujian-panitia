<div>
    <x-slot name="header">Denah Ruang Ujian</x-slot>

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
            <span class="font-medium text-gray-900">Denah Ruang</span>
        </nav>
    </div>

    <!-- Header Info -->
    <div class="mb-6 p-4 rounded-xl bg-gray-50 border border-gray-200">
        <h2 class="text-lg font-semibold text-gray-900">{{ $kegiatanUjian->nama_ujian }}</h2>
        <p class="text-sm text-gray-500 mt-1">{{ $kegiatanUjian->tahunAjaran->nama }} -
            {{ $kegiatanUjian->tahunAjaran->semester }}</p>
    </div>

    @if ($selectedRuangId && $denahData)
        <!-- Denah View -->
        <div class="mb-6">
            <!-- Top Actions -->
            <div class="flex items-center justify-between gap-3 mb-4 flex-wrap">
                <div class="flex items-center gap-3">
                    <button wire:click="clearSelection" class="btn btn-secondary">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali
                    </button>
                    <button wire:click="toggleCover" class="btn {{ $showCover ? 'btn-primary' : 'btn-secondary' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg>
                        {{ $showCover ? 'Lihat Denah' : 'Lihat Cover' }}
                    </button>
                </div>
            </div>

            @if ($showCover && $coverData)
                <!-- Room Cover Print -->
                <div class="card" id="cover-print-area">
                    <div class="p-6 border-b border-gray-200 flex items-center justify-between print:hidden">
                        <h3 class="text-lg font-semibold text-gray-900">
                            Cover Ruangan - {{ $coverData['ruang']['kode'] }}
                        </h3>
                        <button onclick="printCover()" class="btn btn-primary">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                </path>
                            </svg>
                            Cetak Cover
                        </button>
                    </div>

                    <!-- Cover Layout -->
                    <div class="cover-print-content p-8 flex items-center justify-center min-h-[600px]"
                        style="background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); -webkit-print-color-adjust: exact; print-color-adjust: exact;">
                        <div class="relative w-full max-w-xl">
                            <div class="absolute -inset-3 rounded-3xl"
                                style="background: #4f46e5; -webkit-print-color-adjust: exact; print-color-adjust: exact;">
                            </div>
                            <div class="absolute -inset-2 rounded-2xl"
                                style="background: #6366f1; -webkit-print-color-adjust: exact; print-color-adjust: exact;">
                            </div>
                            <div class="relative bg-white rounded-xl shadow-2xl overflow-hidden"
                                style="-webkit-print-color-adjust: exact; print-color-adjust: exact;">
                                <div class="h-6"
                                    style="background: #2563eb; -webkit-print-color-adjust: exact; print-color-adjust: exact;">
                                </div>
                                <div class="p-10 text-center">
                                    <div class="flex justify-center mb-6">
                                        <div class="inline-flex items-center gap-2 px-6 py-2 rounded-full"
                                            style="background: #4f46e5; -webkit-print-color-adjust: exact; print-color-adjust: exact;">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                                </path>
                                            </svg>
                                            <span class="text-white font-bold tracking-wider text-sm">UJIAN
                                                SEKOLAH</span>
                                        </div>
                                    </div>
                                    <h1 class="text-5xl font-black tracking-[0.3em] mb-4" style="color: #1e40af;">RUANG
                                    </h1>
                                    <div class="flex items-center justify-center gap-4 mb-6">
                                        <div class="w-24 h-1 rounded-full" style="background: #3b82f6;"></div>
                                        <div class="w-3 h-3 rounded-full" style="background: #2563eb;"></div>
                                        <div class="w-24 h-1 rounded-full" style="background: #3b82f6;"></div>
                                    </div>
                                    <div class="relative mb-6">
                                        <div class="absolute inset-0 text-[180px] font-black leading-none text-center select-none"
                                            style="color: #c7d2fe; transform: translate(4px, 4px);">
                                            {{ str_pad(preg_replace('/[^0-9]/', '', $coverData['ruang']['kode']), 2, '0', STR_PAD_LEFT) }}
                                        </div>
                                        <div class="relative text-[180px] font-black leading-none"
                                            style="color: #1e3a8a;">
                                            {{ str_pad(preg_replace('/[^0-9]/', '', $coverData['ruang']['kode']), 2, '0', STR_PAD_LEFT) }}
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-center gap-4 mb-6">
                                        <div class="w-32 h-0.5 rounded-full" style="background: #6366f1;"></div>
                                        <div class="w-32 h-0.5 rounded-full" style="background: #6366f1;"></div>
                                    </div>
                                    <div class="relative mx-auto max-w-md">
                                        <div class="absolute inset-0 rounded-xl transform rotate-1"
                                            style="background: #4f46e5;"></div>
                                        <div class="relative rounded-xl p-6"
                                            style="background: white; border: 3px solid #6366f1;">
                                            <div class="inline-flex items-center gap-2 font-bold text-sm tracking-wider mb-3"
                                                style="color: #4f46e5;">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1">
                                                    </path>
                                                </svg>
                                                NOMOR PESERTA UJIAN
                                            </div>
                                            <div class="text-2xl font-bold font-mono tracking-wider"
                                                style="color: #1e293b;">
                                                {{ $coverData['nomor_awal'] }}
                                                <span style="color: #4f46e5;">s.d.</span>
                                                {{ $coverData['nomor_akhir'] }}
                                            </div>
                                            <div class="mt-2 text-sm" style="color: #64748b;">
                                                Total: {{ $coverData['total_siswa'] }} peserta
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="h-6"
                                    style="background: #4f46e5; -webkit-print-color-adjust: exact; print-color-adjust: exact;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Settings Panel -->
                <div class="card mb-4 print:hidden">
                    <div class="p-4 border-b border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900">⚙️ Pengaturan Denah</h3>
                    </div>
                    <div class="p-4">
                        <div class="flex flex-wrap items-end gap-6">
                            <!-- Jumlah Kolom -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Jumlah Kolom</label>
                                <input type="number" wire:model.live="kolom" min="1" max="10"
                                    class="input w-20 text-center text-sm">
                            </div>

                            <!-- Siswa per Meja -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Siswa per Bangku</label>
                                <select wire:model.live="siswaPerMeja" class="input text-sm">
                                    <option value="1">1 siswa / bangku</option>
                                    <option value="2">2 siswa / bangku</option>
                                </select>
                            </div>

                            <!-- Urutan Kursi -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Pola Urutan</label>
                                <select wire:model.live="urutanKursi" class="input text-sm">
                                    <option value="zigzag">Zigzag (S-Pattern)</option>
                                    <option value="lurus">Lurus (Kiri ke Kanan)</option>
                                </select>
                            </div>

                            <!-- Info Badges -->
                            <div class="flex items-center gap-3 text-sm text-gray-500">
                                <span class="badge badge-primary">{{ $denahData['total_siswa'] }} siswa</span>
                                <span class="badge badge-secondary">{{ $denahData['total_meja'] }} bangku</span>
                                <span class="badge badge-secondary">{{ count($denahData['rows']) }} baris</span>
                            </div>
                        </div>

                        <!-- Hint -->
                        <div class="mt-3 flex items-center gap-2 text-xs text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            @if ($urutanKursi === 'zigzag')
                                <span>Urutan zigzag: baris ganjil kiri→kanan, baris genap kanan→kiri</span>
                            @else
                                <span>Urutan lurus: semua baris dari kiri ke kanan</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Printable Denah -->
                <div class="card" id="denah-print-area">
                    <div class="p-6 border-b border-gray-200 flex items-center justify-between print:hidden">
                        <h3 class="text-lg font-semibold text-gray-900">
                            Denah {{ $denahData['ruang']['kode'] }} - {{ $denahData['ruang']['nama'] }}
                        </h3>
                        <button onclick="printDenah()" class="btn btn-primary">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                </path>
                            </svg>
                            Cetak Denah
                        </button>
                    </div>

                    <!-- Print Layout -->
                    <div class="p-8 print:p-4 denah-content">
                        <!-- Kop Surat -->
                        <div class="flex items-center gap-4 border-b-4 border-double border-black pb-3 mb-4 kop-surat">
                            <div class="w-16 h-16 flex-shrink-0">
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
                                <div class="text-xs font-bold">KEMENTERIAN AGAMA REPUBLIK INDONESIA</div>
                                <div class="text-lg font-bold">
                                    {{ strtoupper($schoolSettings['nama_sekolah'] ?? 'NAMA SEKOLAH') }}</div>
                                <div class="text-xs">Alamat: {{ $schoolSettings['alamat'] ?? '' }}
                                    {{ $schoolSettings['kelurahan'] ?? '' }} {{ $schoolSettings['kecamatan'] ?? '' }}
                                    Telp. {{ $schoolSettings['telepon'] ?? '' }}</div>
                                <div class="text-xs">{{ strtoupper($schoolSettings['kabupaten'] ?? '') }}
                                    {{ $schoolSettings['kode_pos'] ?? '' }}</div>
                            </div>
                        </div>

                        <!-- Room Layout Container -->
                        <div class="border-4 border-green-600 rounded-lg p-4 bg-white room-container"
                            style="border-color: #16a34a; -webkit-print-color-adjust: exact; print-color-adjust: exact;">

                            <!-- Room Title -->
                            <div class="text-center mb-4">
                                <h1 class="text-2xl font-bold border-2 border-black inline-block px-8 py-2">
                                    {{ $denahData['ruang']['nama'] }}
                                </h1>
                            </div>

                            <!-- Header Row: Kode Ruang and Pengawas -->
                            <div class="flex justify-between items-start mb-4">
                                <div class="border-2 border-black px-4 py-2 bg-white">
                                    <span class="font-bold">{{ $denahData['ruang']['kode'] }}</span>
                                </div>
                                <div class="flex items-start gap-2">
                                    <div class="w-4 h-4 rounded-full mt-2"
                                        style="background: #000; -webkit-print-color-adjust: exact; print-color-adjust: exact;">
                                    </div>
                                    <div class="border-2 border-black px-4 py-2 bg-white">
                                        <span class="font-bold">PENGAWAS UJIAN</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Desk Grid (Dynamic Columns) -->
                            <div class="desk-grid space-y-2">
                                @foreach ($denahData['rows'] as $rowIndex => $row)
                                    <div class="grid gap-2"
                                        style="grid-template-columns: repeat({{ $denahData['kolom'] }}, minmax(0, 1fr));">
                                        @foreach ($row as $desk)
                                            @if ($denahData['siswaPerMeja'] === 1)
                                                {{-- Single student per desk --}}
                                                <div class="desk-cell border-2 border-black bg-white">
                                                    <div class="p-1.5 text-center">
                                                        @if (!empty($desk['students'][0]))
                                                            <div
                                                                class="desk-name font-medium text-[10px] leading-tight break-words min-h-[20px]">
                                                                {{ $desk['students'][0]['nama'] }}
                                                            </div>
                                                            <div class="desk-kelas text-[8px] text-gray-500 mt-0.5">
                                                                {{ $desk['students'][0]['asal_kelas'] }}
                                                            </div>
                                                        @else
                                                            <div class="text-gray-400 min-h-[20px] text-xs">-</div>
                                                        @endif
                                                    </div>
                                                    <div class="flex justify-center py-1 border-t border-black">
                                                        <div class="seat-dot w-4 h-4 rounded-full"
                                                            style="background: #000; -webkit-print-color-adjust: exact; print-color-adjust: exact;">
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                {{-- Two students per desk --}}
                                                <div class="desk-cell border-2 border-black bg-white">
                                                    <div
                                                        class="text-center text-[8px] font-bold py-0.5 border-b border-black">
                                                        PESERTA UJIAN
                                                    </div>
                                                    <div class="flex text-xs">
                                                        <div class="flex-1 p-1.5 text-center border-r border-black">
                                                            @if (!empty($desk['students'][0]))
                                                                <div
                                                                    class="desk-name font-medium text-[10px] leading-tight break-words min-h-[20px]">
                                                                    {{ $desk['students'][0]['nama'] }}
                                                                </div>
                                                            @else
                                                                <div class="text-gray-400 min-h-[20px]">-</div>
                                                            @endif
                                                        </div>
                                                        <div class="flex-1 p-1.5 text-center">
                                                            @if (!empty($desk['students'][1]))
                                                                <div
                                                                    class="desk-name font-medium text-[10px] leading-tight break-words min-h-[20px]">
                                                                    {{ $desk['students'][1]['nama'] }}
                                                                </div>
                                                            @else
                                                                <div class="text-gray-400 min-h-[20px]">-</div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="flex border-t border-black">
                                                        <div
                                                            class="flex-1 flex justify-center py-1 border-r border-black">
                                                            <div class="seat-dot w-4 h-4 rounded-full"
                                                                style="background: #000; -webkit-print-color-adjust: exact; print-color-adjust: exact;">
                                                            </div>
                                                        </div>
                                                        <div class="flex-1 flex justify-center py-1">
                                                            <div class="seat-dot w-4 h-4 rounded-full"
                                                                style="background: #000; -webkit-print-color-adjust: exact; print-color-adjust: exact;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach

                                        {{-- Fill empty cells in last row --}}
                                        @for ($emptyIdx = count($row); $emptyIdx < $denahData['kolom']; $emptyIdx++)
                                            <div></div>
                                        @endfor
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @else
        <!-- Room List -->
        <div class="card">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Pilih Ruang untuk Melihat Denah</h3>
            </div>

            <div class="p-6">
                @if ($ruangWithPenempatan->isEmpty())
                    <div class="text-center py-8 text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-300 mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7">
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
                                    <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7">
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
                                    <span class="text-blue-600 text-sm font-medium">Lihat Denah →</span>
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
            size: 330mm 215mm landscape;
            margin: 5mm;
        }

        @media print {
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                box-sizing: border-box !important;
            }

            html,
            body {
                margin: 0 !important;
                padding: 0 !important;
                overflow: hidden !important;
            }

            .print\:hidden {
                display: none !important;
            }
        }
    </style>

    <script>
        function printDenah() {
            const rows = {{ count($denahData['rows'] ?? []) }};
            const kolom = {{ $denahData['kolom'] ?? 5 }};
            const siswaPerMeja = {{ $denahData['siswaPerMeja'] ?? 1 }};

            // Font & element sizes scale with row count
            // More rows = smaller everything
            const scale = Math.max(0.4, Math.min(1, 7 / rows));
            const nameFontSize = Math.max(4, 8 * scale);
            const kelasFontSize = Math.max(3, 6 * scale);
            const headerFontSize = Math.max(3, 5 * scale);
            const seatSize = Math.max(1.5, 3 * scale);
            const cellPad = Math.max(0.3, 1.2 * scale);
            const seatPadY = Math.max(0.2, 0.8 * scale);
            const rowGap = Math.max(0.5, 1.5 * scale);
            const colGap = Math.max(0.5, 1.5 * scale);

            const styleEl = document.createElement('style');
            styleEl.id = 'denah-print-style';
            styleEl.textContent = `
                @page {
                    size: 330mm 215mm !important;
                    margin: 5mm !important;
                }
                @media print {
                    * {
                        -webkit-print-color-adjust: exact !important;
                        print-color-adjust: exact !important;
                        box-sizing: border-box !important;
                    }
                    html, body {
                        margin: 0 !important;
                        padding: 0 !important;
                        width: 320mm !important;
                        height: 205mm !important;
                        overflow: hidden !important;
                    }
                    body * {
                        visibility: hidden !important;
                    }
                    #denah-print-area,
                    #denah-print-area * {
                        visibility: visible !important;
                    }
                    #denah-print-area {
                        position: fixed !important;
                        left: 0 !important;
                        top: 0 !important;
                        width: 320mm !important;
                        height: 205mm !important;
                        overflow: hidden !important;
                        padding: 0 !important;
                        margin: 0 !important;
                        background: white !important;
                    }
                    #denah-print-area > .p-6.border-b {
                        display: none !important;
                    }

                    /* Main content - fills page exactly */
                    .denah-content {
                        padding: 2mm !important;
                        width: 320mm !important;
                        height: 205mm !important;
                        overflow: hidden !important;
                        display: flex !important;
                        flex-direction: column !important;
                    }

                    /* === Kop Surat === */
                    .kop-surat {
                        padding-bottom: 1mm !important;
                        margin-bottom: 1.5mm !important;
                        gap: 3mm !important;
                        flex-shrink: 0 !important;
                    }
                    .kop-surat .w-16.h-16 {
                        width: 10mm !important;
                        height: 10mm !important;
                    }
                    .kop-surat .text-xs {
                        font-size: 7pt !important;
                        line-height: 1.2 !important;
                    }
                    .kop-surat .text-lg {
                        font-size: 9pt !important;
                        line-height: 1.2 !important;
                    }

                    /* === Room Container - takes all remaining space === */
                    .room-container {
                        padding: 2mm !important;
                        border-width: 1.5px !important;
                        flex: 1 !important;
                        min-height: 0 !important;
                        display: flex !important;
                        flex-direction: column !important;
                        overflow: hidden !important;
                    }
                    .room-container > .text-center.mb-4 {
                        margin-bottom: 1.5mm !important;
                        flex-shrink: 0 !important;
                    }
                    .room-container .text-2xl {
                        font-size: 10pt !important;
                    }
                    .room-container .px-8.py-2 {
                        padding: 1mm 3mm !important;
                        border-width: 1px !important;
                    }
                    .room-container > .flex.justify-between {
                        margin-bottom: 1.5mm !important;
                        flex-shrink: 0 !important;
                    }
                    .room-container .px-4.py-2 {
                        padding: 0.5mm 1.5mm !important;
                        font-size: 7pt !important;
                        border-width: 1px !important;
                    }
                    .room-container .w-4.h-4.rounded-full {
                        width: 2mm !important;
                        height: 2mm !important;
                    }

                    /* === Desk Grid - flex grow to fill remaining height === */
                    .desk-grid {
                        flex: 1 !important;
                        min-height: 0 !important;
                        display: flex !important;
                        flex-direction: column !important;
                        gap: ${rowGap}mm !important;
                    }
                    /* Reset tailwind space-y */
                    .desk-grid.space-y-2 > * + * {
                        margin-top: 0 !important;
                    }

                    /* Each row stretches equally */
                    .desk-grid > .grid {
                        flex: 1 !important;
                        min-height: 0 !important;
                        gap: ${colGap}mm !important;
                        align-content: stretch !important;
                    }

                    /* Desk cell fills its grid area */
                    .desk-cell {
                        border-width: 0.5pt !important;
                        overflow: hidden !important;
                        height: 100% !important;
                        display: flex !important;
                        flex-direction: column !important;
                        text-align: center !important;
                    }

                    /* Names */
                    .desk-name {
                        font-size: ${nameFontSize}pt !important;
                        line-height: 1.15 !important;
                        text-align: center !important;
                        overflow: hidden !important;
                        word-break: break-word !important;
                    }
                    .desk-kelas {
                        font-size: ${kelasFontSize}pt !important;
                        line-height: 1 !important;
                        margin-top: 0.2mm !important;
                        text-align: center !important;
                    }
                    .desk-cell .min-h-\\[20px\\] {
                        min-height: 0 !important;
                    }

                    /* Content area (1 siswa) - fills middle */
                    .desk-cell > .p-1\\.5 {
                        padding: ${cellPad}mm !important;
                        flex: 1 !important;
                        min-height: 0 !important;
                        display: flex !important;
                        flex-direction: column !important;
                        justify-content: center !important;
                        align-items: center !important;
                    }

                    /* Header text (2 siswa) */
                    .desk-cell > .text-center.text-\\[8px\\] {
                        font-size: ${headerFontSize}pt !important;
                        padding: 0.2mm 0 !important;
                        line-height: 1 !important;
                        flex-shrink: 0 !important;
                    }

                    /* Name columns (2 siswa) */
                    .desk-cell > .flex.text-xs {
                        flex: 1 !important;
                        min-height: 0 !important;
                    }
                    .desk-cell > .flex.text-xs > .flex-1 {
                        padding: ${cellPad}mm !important;
                        display: flex !important;
                        align-items: center !important;
                        justify-content: center !important;
                    }

                    /* Seat dots */
                    .desk-cell > .flex.border-t,
                    .desk-cell > .flex.justify-center.py-1 {
                        flex-shrink: 0 !important;
                        padding-top: ${seatPadY}mm !important;
                        padding-bottom: ${seatPadY}mm !important;
                    }
                    .seat-dot {
                        width: ${seatSize}mm !important;
                        height: ${seatSize}mm !important;
                    }

                    .print\\:hidden {
                        display: none !important;
                    }
                }
            `;
            document.head.appendChild(styleEl);
            window.print();
            setTimeout(() => {
                const el = document.getElementById('denah-print-style');
                if (el) el.remove();
            }, 1000);
        }

        function printCover() {
            const styleEl = document.createElement('style');
            styleEl.id = 'cover-print-style';
            styleEl.textContent = `
                @page {
                    size: 215mm 330mm !important;
                    margin: 0 !important;
                }
                @media print {
                    html, body {
                        width: 215mm !important;
                        height: 330mm !important;
                        margin: 0 !important;
                        padding: 0 !important;
                        overflow: hidden !important;
                        background: white !important;
                    }
                    body * {
                        visibility: hidden !important;
                    }
                    #cover-print-area,
                    #cover-print-area * {
                        visibility: visible !important;
                    }
                    #cover-print-area {
                        position: absolute !important;
                        left: 0 !important;
                        top: 0 !important;
                        width: 215mm !important;
                        height: 330mm !important;
                        max-height: 330mm !important;
                        margin: 0 !important;
                        padding: 0 !important;
                        overflow: hidden !important;
                        background: white !important;
                        border: none !important;
                        box-shadow: none !important;
                    }
                    #cover-print-area > .p-6.border-b {
                        display: none !important;
                        visibility: hidden !important;
                    }
                    #cover-print-area .cover-print-content {
                        width: 215mm !important;
                        height: 330mm !important;
                        max-height: 330mm !important;
                        display: flex !important;
                        align-items: center !important;
                        justify-content: center !important;
                        padding: 20mm !important;
                        margin: 0 !important;
                        overflow: hidden !important;
                    }
                    #cover-print-area .max-w-xl {
                        max-width: 160mm !important;
                    }
                    #cover-print-area .text-\\[180px\\] {
                        font-size: 120px !important;
                        line-height: 1 !important;
                    }
                    #cover-print-area .text-5xl {
                        font-size: 28px !important;
                    }
                    #cover-print-area .min-h-\\[600px\\] {
                        min-height: auto !important;
                    }
                }
            `;
            document.head.appendChild(styleEl);
            window.print();
            setTimeout(() => {
                const el = document.getElementById('cover-print-style');
                if (el) el.remove();
            }, 1000);
        }
    </script>
</div>
