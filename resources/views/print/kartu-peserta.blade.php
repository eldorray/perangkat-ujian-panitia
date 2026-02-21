<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Peserta Ujian - {{ $kegiatanUjian->nama_ujian }}</title>
    <style>
        @page {
            size: 330mm 215mm landscape;
            /* F4/Legal Landscape */
            margin: 5mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 7px;
            line-height: 1.2;
            color: #000;
            background: #fff;
        }

        .print-page {
            width: 320mm;
            height: 205mm;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            grid-template-rows: repeat(2, 1fr);
            gap: 4mm;
            page-break-after: always;
        }

        .print-page:last-child {
            page-break-after: auto;
        }

        .card {
            border: 1.5px solid #000;
            padding: 3mm;
            background: #fff;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        /* Header Section */
        .card-header {
            display: flex;
            gap: 2mm;
            border-bottom: 1.5px solid #000;
            padding-bottom: 2mm;
            margin-bottom: 2mm;
        }

        .school-logo {
            width: 12mm;
            height: 12mm;
            border: 1px solid #999;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .school-logo img {
            max-width: 100%;
            max-height: 100%;
        }

        .school-info {
            flex: 1;
            text-align: center;
        }

        .school-info .kop-line {
            font-size: 5px;
            margin-bottom: 0.5px;
        }

        .school-info .school-name {
            font-size: 9px;
            font-weight: bold;
            margin: 1px 0;
        }

        .school-info .school-address {
            font-size: 5px;
        }

        .school-info .school-contact {
            font-size: 5px;
        }

        .school-info .school-city {
            font-size: 6px;
            font-weight: bold;
            text-decoration: underline;
        }

        /* Student Info Section */
        .student-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2mm;
            align-items: flex-start;
        }

        .student-details {
            font-size: 7px;
        }

        .student-details table td {
            padding: 0.5px 0;
            vertical-align: top;
        }

        .student-details .label {
            width: 50px;
            font-size: 6.5px;
        }

        .student-details .separator {
            width: 6px;
        }

        .student-details .value {
            font-weight: bold;
            font-size: 7px;
        }

        .card-title-box {
            border: 1.5px solid #000;
            padding: 1.5mm 3mm;
            text-align: center;
            align-self: center;
        }

        .card-title-box h2 {
            font-size: 8px;
            font-weight: bold;
            letter-spacing: 0.5px;
        }

        /* Schedule Table - made compact and scrollable if needed */
        .schedule-container {
            flex: 1;
            overflow: hidden;
            margin-bottom: 2mm;
        }

        .schedule-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 6px;
        }

        .schedule-table th,
        .schedule-table td {
            border: 1px solid #000;
            padding: 1px 2px;
            text-align: center;
        }

        .schedule-table th {
            background: #333;
            color: #fff;
            font-weight: bold;
            font-size: 5.5px;
        }

        .schedule-table .col-no {
            width: 12px;
        }

        .schedule-table .col-hari {
            width: 50px;
            text-align: left;
        }

        .schedule-table .col-waktu {
            width: 42px;
        }

        .schedule-table .col-mapel {
            width: auto;
            text-align: left;
        }

        .schedule-table .col-ttd {
            width: 30px;
        }

        .schedule-table td {
            font-size: 5.5px;
        }

        .schedule-table .ttd-cell {
            font-size: 4.5px;
            color: #333;
        }

        /* Notes Section */
        .notes {
            font-size: 5px;
            margin-bottom: 2mm;
        }

        .notes-title {
            font-weight: bold;
            font-size: 5.5px;
            margin-bottom: 1px;
        }

        .notes ol {
            margin-left: 8px;
            padding-left: 0;
        }

        .notes li {
            margin-bottom: 0.5px;
        }

        /* Signature Section */
        .signature-section {
            display: flex;
            justify-content: flex-end;
            text-align: center;
            font-size: 6px;
        }

        .signature-box {
            width: 35mm;
        }

        .signature-date {
            font-size: 5.5px;
            margin-bottom: 1px;
        }

        .signature-title {
            font-size: 5.5px;
            margin-bottom: 12mm;
        }

        .signature-name {
            font-weight: bold;
            font-size: 6px;
            text-decoration: underline;
        }

        @media print {
            body {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }

            .no-print {
                display: none !important;
            }

            .card {
                break-inside: avoid;
            }
        }

        .print-actions {
            position: fixed;
            top: 20px;
            right: 20px;
            display: flex;
            gap: 10px;
            z-index: 1000;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: #0d9488;
            color: white;
        }

        .btn-secondary {
            background: #e5e7eb;
            color: #374151;
        }

        .btn:hover {
            opacity: 0.9;
        }

        /* Make schedule table rows compact */
        .compact-row td {
            padding: 0.5px 1px !important;
            font-size: 5px !important;
            line-height: 1.1 !important;
        }
    </style>
</head>

<body>
    <div class="print-actions no-print">
        <button class="btn btn-primary" onclick="window.print()">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                </path>
            </svg>
            Cetak
        </button>
        <button class="btn btn-secondary" onclick="window.close()">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
            Tutup
        </button>
    </div>

    @foreach ($siswas->chunk(4) as $pageIndex => $pageCards)
        <div class="print-page">
            @foreach ($pageCards as $siswa)
                <div class="card">
                    <!-- Header with School Info -->
                    <div class="card-header">
                        <div class="school-logo">
                            @if (!empty($schoolSettings['logo']))
                                <img src="{{ asset('storage/' . $schoolSettings['logo']) }}" alt="Logo">
                            @else
                                <svg width="30" height="30" viewBox="0 0 24 24" fill="#666">
                                    <path
                                        d="M12 3L1 9l4 2.18v6L12 21l7-3.82v-6l2-1.09V17h2V9L12 3zm6.82 6L12 12.72 5.18 9 12 5.28 18.82 9zM17 15.99l-5 2.73-5-2.73v-3.72L12 15l5-2.73v3.72z" />
                                </svg>
                            @endif
                        </div>
                        <div class="school-info">
                            <div class="kop-line">KEMENTERIAN AGAMA REPUBLIK INDONESIA</div>
                            <div class="school-name">{{ $schoolSettings['nama_sekolah'] ?? 'NAMA SEKOLAH' }}</div>
                            <div class="school-address">{{ $schoolSettings['alamat'] ?? '' }}
                                {{ $schoolSettings['kecamatan'] ?? '' }}</div>
                            <div class="school-contact">
                                @if (!empty($schoolSettings['website']))
                                    {{ $schoolSettings['website'] }}
                                @endif
                                @if (!empty($schoolSettings['email']))
                                    | {{ $schoolSettings['email'] }}
                                @endif
                            </div>
                            <div class="school-city">{{ strtoupper($schoolSettings['kabupaten'] ?? 'KOTA') }}</div>
                        </div>
                    </div>

                    <!-- Student Info and Card Title -->
                    <div class="student-info">
                        <div class="student-details">
                            <table>
                                <tr>
                                    <td class="label">No. Peserta</td>
                                    <td class="separator">:</td>
                                    <td class="value">
                                        {{ isset($penempatans[$siswa->id]) ? $penempatans[$siswa->id]->nomor_peserta ?? '-' : '-' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label">Nama</td>
                                    <td class="separator">:</td>
                                    <td class="value">{{ $siswa->nama_lengkap }}</td>
                                </tr>
                                <tr>
                                    <td class="label">Kelas</td>
                                    <td class="separator">:</td>
                                    <td class="value">{{ $siswa->tingkat_rombel ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="label">Ruang Ujian</td>
                                    <td class="separator">:</td>
                                    <td class="value">
                                        @if (isset($penempatans[$siswa->id]))
                                            {{ $penempatans[$siswa->id]->ruangUjian->nama }} (No.
                                            {{ $penempatans[$siswa->id]->nomor_urut }})
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="card-title-box">
                            <h2>KARTU PESERTA</h2>
                        </div>
                    </div>

                    <!-- Schedule Table -->
                    <div class="schedule-container">
                        <table class="schedule-table">
                            <thead>
                                <tr>
                                    <th class="col-no">No</th>
                                    <th class="col-hari">Hari, Tanggal</th>
                                    <th class="col-waktu">Waktu</th>
                                    <th class="col-mapel">Mata Pelajaran</th>
                                    <th class="col-ttd">Ttd</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    // Filter jadwal berdasarkan kelompok kelas siswa
                                    $filteredJadwals = $kegiatanUjian->jadwalUjians
                                        ? $kegiatanUjian->jadwalUjians->filter(function ($jadwal) use ($siswa) {
                                            return $jadwal->matchesSiswa($siswa->tingkat_rombel ?? '');
                                        })
                                        : collect();
                                @endphp
                                @if ($filteredJadwals->count() > 0)
                                    @foreach ($filteredJadwals as $index => $jadwal)
                                        <tr class="compact-row">
                                            <td>{{ $loop->iteration }}</td>
                                            <td class="col-hari">{{ $jadwal->tanggal->translatedFormat('D, d/m/Y') }}
                                            </td>
                                            <td>{{ $jadwal->waktu }}</td>
                                            <td class="col-mapel">{{ $jadwal->mata_pelajaran }}</td>
                                            <td class="ttd-cell"></td>
                                        </tr>
                                    @endforeach
                                @else
                                    @for ($i = 1; $i <= 5; $i++)
                                        <tr class="compact-row">
                                            <td>{{ $i }}</td>
                                            <td>...............</td>
                                            <td>.........</td>
                                            <td>.................</td>
                                            <td class="ttd-cell"></td>
                                        </tr>
                                    @endfor
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <!-- Notes -->
                    <div class="notes">
                        <span class="notes-title">Ket:</span>
                        1. Kartu ini sebagai tanda bukti mengikuti ujian.
                        2. Kartu dibawa selama kegiatan ujian berlangsung.
                        3. Peserta tanpa kartu tidak diperkenankan mengikuti ujian.
                    </div>

                    <!-- Signature -->
                    <div class="signature-section">
                        <div class="signature-box">
                            <div class="signature-date">{{ $schoolSettings['kabupaten'] ?? 'Kota' }},
                                {{ now()->translatedFormat('d F Y') }}</div>
                            <div class="signature-title">Ketua Panitia,</div>
                            <div class="signature-name">
                                {{ $kegiatanUjian->ketua_panitia ?? '.............................' }}</div>
                            @if (!empty($kegiatanUjian->nip_ketua_panitia))
                                <div style="font-size: 5px;">NIP. {{ $kegiatanUjian->nip_ketua_panitia }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach

            {{-- Fill empty cells if less than 4 cards on the last page --}}
            @for ($i = $pageCards->count(); $i < 4; $i++)
                <div class="card" style="visibility: hidden;"></div>
            @endfor
        </div>
    @endforeach
</body>

</html>
