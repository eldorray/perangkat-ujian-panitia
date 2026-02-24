<div>
    <x-slot name="header">Jadwal Mengawas</x-slot>

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
            <span class="font-medium text-gray-900">Jadwal Mengawas</span>
        </nav>
    </div>

    <!-- Header Info -->
    <div class="mb-6 p-4 rounded-xl bg-gray-50 border border-gray-200">
        <h2 class="text-lg font-semibold text-gray-900">{{ $kegiatanUjian->nama_ujian }}</h2>
        <p class="text-sm text-gray-500 mt-1">{{ $kegiatanUjian->tahunAjaran->nama }} -
            {{ $kegiatanUjian->tahunAjaran->semester }}</p>
    </div>

    @if (!$showPreview)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Pilih Pengawas -->
            <div class="card lg:col-span-1">
                <div class="p-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Pilih Pengawas</h3>
                    <p class="text-sm text-gray-500 mt-1">Pilih guru yang akan ditugaskan sebagai pengawas</p>
                </div>

                <div class="p-4 border-b border-gray-200">
                    <input type="text" wire:model.live.debounce.300ms="search" class="form-input w-full"
                        placeholder="Cari nama atau NIP...">
                    <div class="flex gap-2 mt-3">
                        <button wire:click="selectAllPengawas" class="btn btn-secondary btn-sm flex-1">Pilih
                            Semua</button>
                        <button wire:click="clearPengawas" class="btn btn-secondary btn-sm flex-1">Hapus Semua</button>
                    </div>
                </div>

                <div class="p-4 max-h-[400px] overflow-y-auto">
                    @forelse ($guruList as $guru)
                        @php
                            $isSelected = in_array($guru->id, $selectedPengawas);
                            $codeIndex = $isSelected ? array_search($guru->id, $selectedPengawas) + 1 : null;
                        @endphp
                        <label
                            class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-0">
                            <input type="checkbox" wire:click="togglePengawas({{ $guru->id }})"
                                @checked($isSelected) class="form-checkbox h-5 w-5 text-blue-600 rounded">
                            <div class="flex-1">
                                <p class="font-medium text-gray-900">
                                    @if ($isSelected)
                                        <span
                                            class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-blue-600 text-white text-xs mr-2">{{ $codeIndex }}</span>
                                    @endif
                                    {{ $guru->full_name_with_titles }}
                                </p>
                                <p class="text-sm text-gray-500">{{ $guru->nip ?: '-' }}</p>
                            </div>
                        </label>
                    @empty
                        <p class="text-center text-gray-500 py-8">Tidak ada guru ditemukan</p>
                    @endforelse
                </div>

                @if (count($selectedPengawas) > 0)
                    <div class="p-4 border-t border-gray-200 bg-gray-50">
                        <p class="text-sm font-medium text-gray-700">{{ count($selectedPengawas) }} pengawas dipilih
                        </p>
                    </div>
                @endif
            </div>

            <!-- Penugasan per Jadwal & Ruang -->
            <div class="card lg:col-span-2">
                <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Penugasan Pengawas</h3>
                        <p class="text-sm text-gray-500 mt-1">Pilih kode pengawas per ruangan (1 pengawas per ruang,
                            tidak boleh bentrok)</p>
                    </div>
                    <div class="flex gap-2">
                        <button wire:click="autoAssign" class="btn btn-secondary btn-sm" @disabled(count($selectedPengawas) == 0)>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                </path>
                            </svg>
                            Acak Otomatis
                        </button>
                        <button wire:click="clearAssignments" class="btn btn-secondary btn-sm text-red-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                </path>
                            </svg>
                            Reset
                        </button>
                    </div>
                </div>

                @if ($jadwalList->isEmpty())
                    <div class="p-8 text-center text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-300 mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        <p>Belum ada jadwal ujian. Silakan buat jadwal terlebih dahulu.</p>
                    </div>
                @elseif ($ruangList->isEmpty())
                    <div class="p-8 text-center text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-300 mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg>
                        <p>Belum ada ruang ujian. Silakan buat ruang ujian terlebih dahulu.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
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
                            $romanNumerals = [
                                1 => 'I',
                                2 => 'II',
                                3 => 'III',
                                4 => 'IV',
                                5 => 'V',
                                6 => 'VI',
                                7 => 'VII',
                                8 => 'VIII',
                            ];
                            $ruangKelasLabel = [];
                            foreach ($ruangList as $r) {
                                $kn = null;
                                foreach ($printGroups as $pg) {
                                    foreach ($pg['kelasList'] as $kls) {
                                        if ($kls['ruang_id'] == $r->id) {
                                            $kn = $kls['short'];
                                            break 2;
                                        }
                                    }
                                }
                                $ruangKelasLabel[$r->id] = $kn;
                            }
                            $dateCounts = $jadwalList->groupBy(fn($j) => $j->tanggal->format('Y-m-d'))->map->count();
                            $prevDateKey = null;
                        @endphp
                        <table class="w-full text-xs border-collapse">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-2 py-2 text-center font-medium text-gray-700 border-b border-r"
                                        rowspan="2">Hari/<br>Tanggal</th>
                                    <th class="px-2 py-2 text-center font-medium text-gray-700 border-b border-r"
                                        rowspan="2">Jam<br>Ke</th>
                                    <th class="px-2 py-2 text-center font-medium text-gray-700 border-b border-r"
                                        rowspan="2">Waktu</th>
                                    <th class="px-2 py-2 text-center font-medium text-gray-700 border-b border-r"
                                        rowspan="2">Mata<br>Pelajaran</th>
                                    <th class="px-1 py-1 text-center font-medium text-gray-700 border-b"
                                        colspan="{{ $ruangList->count() }}">RUANG</th>
                                </tr>
                                <tr>
                                    @foreach ($ruangList as $ruang)
                                        <th
                                            class="px-1 py-1 text-center font-medium text-gray-600 border-b text-[10px] whitespace-nowrap">
                                            @if ($ruangKelasLabel[$ruang->id])
                                                {{ $ruangKelasLabel[$ruang->id] }}<br>
                                            @endif
                                            <span class="text-gray-400">{{ $ruang->kode }}</span>
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($jadwalList as $index => $jadwal)
                                    @php
                                        $dateKey = $jadwal->tanggal->format('Y-m-d');
                                        $isNewDate = $dateKey !== $prevDateKey;
                                        $dateRowspan = $dateCounts[$dateKey] ?? 1;
                                        $prevDateKey = $dateKey;
                                    @endphp
                                    <tr
                                        class="hover:bg-gray-50/50 {{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50/30' }}">
                                        @if ($isNewDate)
                                            <td class="px-2 py-2 border-b border-r text-center font-medium whitespace-nowrap"
                                                rowspan="{{ $dateRowspan }}">
                                                {{ $hariIndonesia[$jadwal->tanggal->format('l')] }}<br>
                                                <span
                                                    class="text-gray-500 text-[10px]">{{ $jadwal->tanggal->format('d/m/Y') }}</span>
                                            </td>
                                        @endif
                                        <td class="px-2 py-1 border-b border-r text-center font-bold">
                                            {{ $romanNumerals[$jadwal->sort_order] ?? $jadwal->sort_order }}</td>
                                        <td
                                            class="px-2 py-1 border-b border-r text-center whitespace-nowrap text-[10px]">
                                            {{ $jadwal->waktu }}</td>
                                        <td class="px-2 py-1 border-b border-r text-center text-[10px]">
                                            {{ $jadwal->mata_pelajaran }}</td>
                                        @foreach ($ruangList as $ruang)
                                            @php
                                                $currentValue = $this->getAssignmentValue($jadwal->id, $ruang->id);
                                                $availableCodes = $this->getAvailablePengawas($jadwal->id, $ruang->id);
                                            @endphp
                                            <td class="px-0 py-0 border-b">
                                                <select
                                                    class="w-full text-center text-xs px-0 py-1 border-0 bg-transparent focus:ring-1 focus:ring-blue-500"
                                                    style="min-width:40px;@if ($currentValue && isset($codeToColor[$currentValue])) background-color:{{ $codeToColor[$currentValue] }}; @endif"
                                                    wire:change="updateAssignment({{ $jadwal->id }}, {{ $ruang->id }}, $event.target.value)">
                                                    <option value="">-</option>
                                                    @foreach ($pengawasData as $data)
                                                        @php
                                                            $isSelected = $currentValue === (string) $data['code'];
                                                            $isAvailable = in_array(
                                                                (string) $data['code'],
                                                                $availableCodes,
                                                            );
                                                            $isDisabled = !$isSelected && !$isAvailable;
                                                        @endphp
                                                        <option value="{{ $data['code'] }}"
                                                            @selected($isSelected) @disabled($isDisabled)>
                                                            {{ $data['initial'] }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="p-4 border-t border-gray-200">
                        <button wire:click="togglePreview" class="btn btn-primary">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                </path>
                            </svg>
                            Lihat Preview & Cetak
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <!-- Daftar Kode Pengawas -->
        @if (count($pengawasData) > 0)
            <div class="card mt-6">
                <div class="p-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Kode dan Nama Pengawas</h3>
                    <p class="text-sm text-gray-500 mt-1">Kode pengawas tetap konsisten di semua hari ujian</p>
                </div>
                <div class="p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2 text-sm">
                        @foreach ($pengawasData as $data)
                            @php
                                $beban = $pengawasStats[(string) $data['code']] ?? 0;
                            @endphp
                            <div class="flex items-center gap-3 p-3 rounded-lg border border-gray-100"
                                style="background-color: {{ $data['color'] }}20;">
                                <span
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-full text-sm font-bold flex-shrink-0"
                                    style="background-color: {{ $data['color'] }};">{{ $data['initial'] }}</span>
                                <div class="flex-1 min-w-0">
                                    <p class="font-medium text-gray-900 truncate">
                                        {{ $data['guru']->full_name_with_titles }}</p>
                                    <p class="text-xs text-gray-500">Kode: {{ $data['code'] }} &bull;
                                        {{ $data['guru']->nip ?: '-' }}</p>
                                </div>
                                @if ($beban > 0)
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $beban >= count($jadwalList) ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }}">
                                        {{ $beban }}x
                                    </span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    @else
        <!-- Preview & Print -->
        <div class="mb-4 flex items-center gap-4">
            <button wire:click="togglePreview" class="btn btn-secondary">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </button>
            <button onclick="printJadwal()" class="btn btn-primary">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                    </path>
                </svg>
                Cetak PDF
            </button>
        </div>

        <div class="card" id="print-area">
            <div class="p-6 print-content">
                <!-- Kop Surat -->
                <div class="flex items-center gap-4 border-b-4 border-double border-black pb-3 mb-4">
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
                        <div class="text-base font-bold">
                            {{ strtoupper($schoolSettings['nama_sekolah'] ?? 'NAMA SEKOLAH') }}</div>
                        <div class="text-xs">Alamat: {{ $schoolSettings['alamat'] ?? '' }}
                            {{ $schoolSettings['kelurahan'] ?? '' }} {{ $schoolSettings['kecamatan'] ?? '' }}
                            Telp. {{ $schoolSettings['telepon'] ?? '' }}</div>
                        <div class="text-xs">{{ strtoupper($schoolSettings['kabupaten'] ?? '') }}
                            {{ $schoolSettings['kode_pos'] ?? '' }}</div>
                    </div>
                </div>

                <!-- Title -->
                <div class="text-center mb-4">
                    <h2 class="text-lg font-bold">JADWAL MENGAWAS</h2>
                    <p class="text-sm">{{ $kegiatanUjian->nama_ujian }}</p>
                    <p class="text-sm">Tahun Ajaran {{ $kegiatanUjian->tahunAjaran->nama }}</p>
                </div>

                <!-- Table Jadwal (same layout as edit) -->
                <div class="overflow-x-auto mb-4">
                    @php
                        $hariPrint = [
                            'Sunday' => 'MINGGU',
                            'Monday' => 'SENIN',
                            'Tuesday' => 'SELASA',
                            'Wednesday' => 'RABU',
                            'Thursday' => 'KAMIS',
                            'Friday' => 'JUMAT',
                            'Saturday' => 'SABTU',
                        ];
                        $romanPrint = [
                            1 => 'I',
                            2 => 'II',
                            3 => 'III',
                            4 => 'IV',
                            5 => 'V',
                            6 => 'VI',
                            7 => 'VII',
                            8 => 'VIII',
                        ];
                        $printRuangKelasLabel = [];
                        foreach ($ruangList as $r) {
                            $kn = null;
                            foreach ($printGroups as $pg) {
                                foreach ($pg['kelasList'] as $kls) {
                                    if ($kls['ruang_id'] == $r->id) {
                                        $kn = $kls['short'];
                                        break 2;
                                    }
                                }
                            }
                            $printRuangKelasLabel[$r->id] = $kn;
                        }
                        $printDateCounts = $jadwalList->groupBy(fn($j) => $j->tanggal->format('Y-m-d'))->map->count();
                        $printPrevDate = null;
                    @endphp
                    <table class="w-full border-collapse border border-black" style="font-size: 7pt;">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border border-black px-1 py-1" rowspan="2">HARI/<br>TANGGAL</th>
                                <th class="border border-black px-1 py-1" rowspan="2">JAM<br>KE</th>
                                <th class="border border-black px-1 py-1" rowspan="2">WAKTU</th>
                                <th class="border border-black px-1 py-1" rowspan="2">MATA<br>PELAJARAN</th>
                                <th class="border border-black px-1 py-1 text-center"
                                    colspan="{{ $ruangList->count() }}">RUANG</th>
                            </tr>
                            <tr class="bg-gray-100">
                                @foreach ($ruangList as $ruang)
                                    <th class="border border-black px-1 py-1 text-center" style="min-width:22px;">
                                        @if ($printRuangKelasLabel[$ruang->id])
                                            {{ $printRuangKelasLabel[$ruang->id] }}/<br>
                                        @endif{{ $ruang->kode }}
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($jadwalList as $jadwal)
                                @php
                                    $dateKey = $jadwal->tanggal->format('Y-m-d');
                                    $isNewDate = $dateKey !== $printPrevDate;
                                    $dateRowspan = $printDateCounts[$dateKey] ?? 1;
                                    $printPrevDate = $dateKey;
                                @endphp
                                <tr>
                                    @if ($isNewDate)
                                        <td class="border border-black px-1 py-1 text-center font-bold"
                                            rowspan="{{ $dateRowspan }}" style="white-space:nowrap;">
                                            {{ $hariPrint[$jadwal->tanggal->format('l')] }},<br>{{ $jadwal->tanggal->format('d/m/Y') }}
                                        </td>
                                    @endif
                                    <td class="border border-black px-1 py-1 text-center font-bold">
                                        {{ $romanPrint[$jadwal->sort_order] ?? $jadwal->sort_order }}</td>
                                    <td class="border border-black px-1 py-1 text-center" style="white-space:nowrap;">
                                        {{ $jadwal->waktu }}</td>
                                    <td class="border border-black px-1 py-1 text-center">
                                        {{ $jadwal->mata_pelajaran }}</td>
                                    @foreach ($ruangList as $ruang)
                                        @php
                                            $code = $assignments[$jadwal->id][$ruang->id] ?? '';
                                            $initial = $code ? $codeToInitial[$code] ?? $code : '';
                                            $color = $code ? $codeToColor[$code] ?? '#eee' : '';
                                        @endphp
                                        <td class="border border-black px-1 py-1 text-center font-bold"
                                            @if ($color) style="background-color: {{ $color }};" @endif>
                                            {{ $initial ?: '-' }}
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Kode Pengawas Legend -->
                @if (count($pengawasData) > 0)
                    <div class="mt-3">
                        <div class="grid grid-cols-4 gap-1" style="font-size: 7pt;">
                            @foreach ($pengawasData as $data)
                                <div class="flex items-center gap-1 border border-gray-300 rounded px-1 py-0.5">
                                    <span class="inline-block px-1 py-0.5 rounded font-bold text-center"
                                        style="background-color: {{ $data['color'] }}; min-width:20px;">{{ $data['initial'] }}</span>
                                    <span class="truncate">{{ $data['guru']->full_name_with_titles }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Footer -->
                <div class="mt-6 flex justify-end">
                    <div class="text-center">
                        <p class="text-sm">{{ $schoolSettings['kabupaten'] ?? '' }},
                            {{ $kegiatanUjian->tanggal_dokumen ? $kegiatanUjian->tanggal_dokumen->translatedFormat('d F Y') : '.........................' }}
                        </p>
                        <p class="text-sm font-bold mt-1">Ketua Panitia</p>
                        <div class="h-16"></div>
                        <p class="text-sm font-bold">
                            {{ $kegiatanUjian->ketua_panitia ?? '____________________________' }}</p>
                        <p class="text-sm">NIP.
                            {{ $kegiatanUjian->nip_ketua_panitia ?? '................................' }}</p>
                    </div>
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
                padding: 10mm;
                box-shadow: none !important;
                border: none !important;
            }

            @page {
                size: A4 landscape;
                margin: 10mm;
            }

            .card {
                box-shadow: none !important;
                border: none !important;
            }
        }
    </style>

    <script>
        function printJadwal() {
            window.print();
        }
    </script>
</div>
