<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Ujian - {{ $kegiatanUjian->nama_ujian }}</title>
    <style>
        @page {
            size: A4;
            margin: 10mm 15mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            line-height: 1.4;
            color: #000;
            background: #fff;
        }

        .container {
            max-width: 210mm;
            margin: 0 auto;
            padding: 5mm;
        }

        /* Kop Surat */
        .kop-surat {
            display: flex;
            align-items: center;
            gap: 15px;
            border-bottom: 3px double #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .kop-logo {
            width: 60px;
            height: 60px;
            flex-shrink: 0;
        }

        .kop-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .kop-text {
            flex: 1;
            text-align: center;
        }

        .kop-institution {
            font-size: 10pt;
            font-weight: bold;
        }

        .kop-school {
            font-size: 14pt;
            font-weight: bold;
        }

        .kop-address {
            font-size: 9pt;
        }

        /* Judul */
        .title {
            text-align: center;
            margin-bottom: 20px;
        }

        .title h1 {
            font-size: 14pt;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 5px;
        }

        .title .subtitle {
            font-size: 12pt;
        }

        /* Table */
        .jadwal-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .jadwal-table th,
        .jadwal-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            text-align: left;
        }

        .jadwal-table th {
            background: #f0f0f0;
            font-weight: bold;
            text-align: center;
        }

        .jadwal-table .col-no {
            width: 30px;
            text-align: center;
        }

        .jadwal-table .col-hari {
            width: 150px;
        }

        .jadwal-table .col-waktu {
            width: 100px;
            text-align: center;
        }

        .date-header {
            background: #e5e5e5;
            font-weight: bold;
        }

        /* Signature */
        .signature {
            margin-top: 30px;
            display: flex;
            justify-content: flex-end;
        }

        .signature-box {
            text-align: center;
            width: 250px;
        }

        .signature-date {
            margin-bottom: 5px;
        }

        .signature-title {
            margin-bottom: 60px;
        }

        .signature-name {
            font-weight: bold;
            text-decoration: underline;
        }

        .signature-nip {
            font-size: 10pt;
        }

        /* Print Actions */
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
            background: #1f2937;
            color: white;
        }

        .btn-secondary {
            background: #e5e7eb;
            color: #374151;
        }

        @media print {
            .print-actions {
                display: none !important;
            }
        }
    </style>
</head>

<body>
    <div class="print-actions">
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

    <div class="container">
        <!-- Kop Surat -->
        <div class="kop-surat">
            <div class="kop-logo">
                @if (!empty($schoolSettings['logo']))
                    <img src="{{ asset('storage/' . $schoolSettings['logo']) }}" alt="Logo">
                @endif
            </div>
            <div class="kop-text">
                <div class="kop-institution">KEMENTERIAN AGAMA REPUBLIK INDONESIA</div>
                <div class="kop-school">{{ strtoupper($schoolSettings['nama_sekolah'] ?? 'NAMA SEKOLAH') }}</div>
                <div class="kop-address">{{ $schoolSettings['alamat'] ?? '' }} {{ $schoolSettings['kecamatan'] ?? '' }}
                    {{ $schoolSettings['kabupaten'] ?? '' }}</div>
            </div>
        </div>

        <!-- Judul -->
        <div class="title">
            <h1>JADWAL UJIAN</h1>
            <div class="subtitle">{{ $kegiatanUjian->nama_ujian }}</div>
            <div class="subtitle">{{ $kegiatanUjian->tahunAjaran->nama }} - {{ $kegiatanUjian->tahunAjaran->semester }}
            </div>
        </div>

        <!-- Tabel Jadwal per Kelompok Kelas -->
        @foreach ($jadwalGrouped as $kelompok => $jadwalByDate)
            @if ($jadwalGrouped->count() > 1)
                <h2
                    style="font-size: 12pt; font-weight: bold; margin-top: 15px; margin-bottom: 10px; text-align: center; background: #f0f0f0; padding: 5px;">
                    {{ $kelompok }}
                </h2>
            @endif

            <table class="jadwal-table">
                <thead>
                    <tr>
                        <th class="col-no">No</th>
                        <th class="col-hari">Hari/Tanggal</th>
                        <th class="col-waktu">Waktu</th>
                        <th>Mata Pelajaran</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp
                    @foreach ($jadwalByDate as $tanggal => $jadwals)
                        @foreach ($jadwals as $index => $jadwal)
                            <tr>
                                <td class="col-no">{{ $no++ }}</td>
                                @if ($index === 0)
                                    <td class="col-hari" rowspan="{{ $jadwals->count() }}">{{ $jadwal->hari_tanggal }}
                                    </td>
                                @endif
                                <td class="col-waktu">{{ $jadwal->waktu }}</td>
                                <td>{{ $jadwal->mata_pelajaran }}</td>
                                <td>{{ $jadwal->keterangan ?? '-' }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        @endforeach

        <!-- Tanda Tangan -->
        <div class="signature">
            <div class="signature-box">
                <div class="signature-date">{{ $schoolSettings['kabupaten'] ?? 'Kota' }},
                    {{ now()->translatedFormat('d F Y') }}</div>
                <div class="signature-title">Kepala Sekolah,</div>
                <div class="signature-name">{{ $schoolSettings['kepala_sekolah'] ?? '.............................' }}
                </div>
                @if (!empty($schoolSettings['nip_kepala_sekolah']))
                    <div class="signature-nip">NIP. {{ $schoolSettings['nip_kepala_sekolah'] }}</div>
                @endif
            </div>
        </div>
    </div>
</body>

</html>
