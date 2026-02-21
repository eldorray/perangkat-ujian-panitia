<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LPJ Panitia - {{ $kegiatanUjian->nama_ujian }}</title>
    <style>
        @page {
            size: A4;
            margin: 15mm 20mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.6;
            color: #000;
            background: #fff;
        }

        .page {
            max-width: 210mm;
            margin: 0 auto;
            padding: 5mm 5mm;
        }

        /* Kop Surat */
        .kop-surat {
            display: flex;
            align-items: center;
            gap: 15px;
            border-bottom: 3px double #000;
            padding-bottom: 10px;
            margin-bottom: 25px;
        }

        .kop-logo {
            width: 70px;
            height: 70px;
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
            font-size: 11pt;
            font-weight: bold;
            margin-bottom: 2px;
        }

        .kop-school {
            font-size: 16pt;
            font-weight: bold;
            margin-bottom: 2px;
        }

        .kop-address {
            font-size: 10pt;
        }

        .kop-contact {
            font-size: 9pt;
        }

        /* Title */
        .report-title {
            text-align: center;
            margin-bottom: 25px;
        }

        .report-title h1 {
            font-size: 14pt;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 3px;
        }

        .report-title .sub {
            font-size: 12pt;
            font-weight: bold;
            margin-bottom: 2px;
        }

        .report-title .nomor {
            font-size: 11pt;
        }

        /* BAB Headers */
        .bab-header {
            font-size: 12pt;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
        }

        .sub-header {
            font-size: 12pt;
            font-weight: bold;
            margin-top: 12px;
            margin-bottom: 8px;
            padding-left: 20px;
        }

        /* Content */
        .content-text {
            text-align: justify;
            margin-bottom: 10px;
            text-indent: 40px;
        }

        .content-text-noindent {
            text-align: justify;
            margin-bottom: 10px;
        }

        .content-list {
            list-style: none;
            padding-left: 30px;
            margin-bottom: 10px;
        }

        .content-list li {
            margin-bottom: 4px;
            text-align: justify;
        }

        /* Quill HTML content styling */
        .content-html {
            text-align: justify;
            margin-bottom: 10px;
        }

        .content-html p {
            text-indent: 40px;
            margin-bottom: 6px;
        }

        .content-html.no-indent p {
            text-indent: 0;
        }

        .content-html ol,
        .content-html ul {
            padding-left: 30px;
            margin-bottom: 6px;
        }

        .content-html li {
            margin-bottom: 3px;
        }

        /* Panitia Table */
        .panitia-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0 15px 0;
            font-size: 11pt;
        }

        .panitia-table th,
        .panitia-table td {
            border: 1px solid #000;
            padding: 5px 8px;
        }

        .panitia-table th {
            background: #f0f0f0;
            font-weight: bold;
            text-align: center;
        }

        .panitia-table td.center {
            text-align: center;
        }

        /* Signature */
        .signature-section {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
            page-break-inside: avoid;
        }

        .signature-box {
            text-align: center;
            width: 45%;
        }

        .signature-date {
            margin-bottom: 5px;
            text-align: left;
        }

        .signature-title {
            margin-bottom: 70px;
        }

        .signature-name {
            font-weight: bold;
            text-decoration: underline;
        }

        .signature-nip {
            font-size: 11pt;
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

            body {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }

            .page {
                padding: 0;
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

    <div class="page">
        <!-- Kop Surat -->
        <div class="kop-surat">
            <div class="kop-logo">
                @if (!empty($schoolSettings['logo']))
                    <img src="{{ asset('storage/' . $schoolSettings['logo']) }}" alt="Logo">
                @else
                    <svg viewBox="0 0 24 24" fill="#666">
                        <path
                            d="M12 3L1 9l4 2.18v6L12 21l7-3.82v-6l2-1.09V17h2V9L12 3zm6.82 6L12 12.72 5.18 9 12 5.28 18.82 9zM17 15.99l-5 2.73-5-2.73v-3.72L12 15l5-2.73v3.72z" />
                    </svg>
                @endif
            </div>
            <div class="kop-text">
                <div class="kop-institution">KEMENTERIAN AGAMA REPUBLIK INDONESIA</div>
                <div class="kop-school">{{ strtoupper($schoolSettings['nama_sekolah'] ?? 'NAMA SEKOLAH') }}</div>
                <div class="kop-address">Alamat: {{ $schoolSettings['alamat'] ?? '' }}
                    {{ $schoolSettings['kelurahan'] ?? '' }} {{ $schoolSettings['kecamatan'] ?? '' }} Telp.
                    {{ $schoolSettings['telepon'] ?? '' }}</div>
                <div class="kop-contact">{{ strtoupper($schoolSettings['kabupaten'] ?? '') }}
                    {{ $schoolSettings['kode_pos'] ?? '' }}</div>
            </div>
        </div>

        <!-- Title -->
        <div class="report-title">
            <h1>LAPORAN PERTANGGUNGJAWABAN (LPJ)</h1>
            <div class="sub">PANITIA {{ strtoupper($kegiatanUjian->nama_ujian) }}</div>
            <div class="sub">{{ strtoupper($schoolSettings['nama_sekolah'] ?? '') }}</div>
            <div class="sub">TAHUN PELAJARAN {{ strtoupper($kegiatanUjian->tahunAjaran->nama ?? '') }}</div>
            @if ($lpj->nomor_surat)
                <div class="nomor" style="margin-top: 5px;">Nomor: {{ $lpj->nomor_surat }}</div>
            @endif
        </div>

        <!-- BAB I: PENDAHULUAN -->
        <div class="bab-header">BAB I<br>PENDAHULUAN</div>

        <div class="sub-header">A. Latar Belakang</div>
        <div class="content-html">{!! $lpj->pendahuluan !!}</div>

        @if (!empty($lpj->dasar_pelaksanaan))
            <div class="sub-header">B. Dasar Pelaksanaan</div>
            <ol class="content-list">
                @foreach ($lpj->dasar_pelaksanaan as $index => $item)
                    <li>{{ $index + 1 }}. {{ $item }}</li>
                @endforeach
            </ol>
        @endif

        @if (!empty($lpj->tujuan))
            <div class="sub-header">C. Tujuan</div>
            <ol class="content-list">
                @foreach ($lpj->tujuan as $index => $item)
                    <li>{{ $index + 1 }}. {{ $item }}</li>
                @endforeach
            </ol>
        @endif

        @if ($lpj->waktu_tempat)
            <div class="sub-header">D. Waktu dan Tempat Pelaksanaan</div>
            <div class="content-html">{!! $lpj->waktu_tempat !!}</div>
        @endif

        <!-- Susunan Panitia (from SK or manual text) -->
        <div class="sub-header">E. Susunan Panitia</div>
        @if (!empty($panitiaByJabatan))
            <table class="panitia-table">
                <thead>
                    <tr>
                        <th style="width: 40px;">No</th>
                        <th>Jabatan</th>
                        <th>Nama</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp
                    @foreach ($panitiaByJabatan as $jabatan => $gurus)
                        @foreach ($gurus as $guru)
                            <tr>
                                <td class="center">{{ $no++ }}</td>
                                <td>{{ $jabatan }}</td>
                                <td>{{ $guru->full_name_with_titles }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        @endif
        @if ($lpj->susunan_panitia_text)
            <div class="content-html no-indent">{!! $lpj->susunan_panitia_text !!}</div>
        @endif
        @if (empty($panitiaByJabatan) && !$lpj->susunan_panitia_text)
            <p class="content-text-noindent" style="font-style: italic; color: #666;">(Susunan panitia belum tersedia.
                Buat Surat Keputusan terlebih dahulu.)</p>
        @endif

        <!-- BAB II: PELAKSANAAN KEGIATAN -->
        <div class="bab-header">BAB II<br>PELAKSANAAN KEGIATAN</div>

        @if (!empty($lpj->pelaksanaan_kegiatan))
            <div class="sub-header">A. Uraian Pelaksanaan</div>
            <ol class="content-list">
                @foreach ($lpj->pelaksanaan_kegiatan as $index => $item)
                    <li>{{ $index + 1 }}. {{ $item }}</li>
                @endforeach
            </ol>
        @endif

        @if (!empty($lpj->hasil_pelaksanaan))
            <div class="sub-header">B. Hasil Pelaksanaan</div>
            <ol class="content-list">
                @foreach ($lpj->hasil_pelaksanaan as $index => $item)
                    <li>{{ $index + 1 }}. {{ $item }}</li>
                @endforeach
            </ol>
        @endif

        @if (!empty($lpj->kendala_hambatan))
            <div class="sub-header">C. Kendala / Hambatan</div>
            <ol class="content-list">
                @foreach ($lpj->kendala_hambatan as $index => $item)
                    <li>{{ $index + 1 }}. {{ $item }}</li>
                @endforeach
            </ol>
        @endif

        <!-- BAB III: KESIMPULAN DAN SARAN -->
        <div class="bab-header">BAB III<br>KESIMPULAN DAN SARAN</div>

        <div class="sub-header">A. Kesimpulan</div>
        <div class="content-html">{!! $lpj->kesimpulan !!}</div>

        @if (!empty($lpj->saran))
            <div class="sub-header">B. Saran</div>
            <ol class="content-list">
                @foreach ($lpj->saran as $index => $item)
                    <li>{{ $index + 1 }}. {{ $item }}</li>
                @endforeach
            </ol>
        @endif

        <!-- BAB IV: PENUTUP -->
        <div class="bab-header">BAB IV<br>PENUTUP</div>

        <div class="content-html">{!! $lpj->penutup !!}</div>

        <!-- Tanda Tangan -->
        <div class="signature-section">
            <div class="signature-box">
                <div class="signature-title">Mengetahui,<br>Ketua Panitia</div>
                <div class="signature-name">
                    {{ $lpj->nama_kepala_sekolah ?? ($schoolSettings['kepala_sekolah'] ?? '.............................') }}
                </div>
                @if ($lpj->nip_kepala_sekolah ?? ($schoolSettings['nip_kepala_sekolah'] ?? ''))
                    <div class="signature-nip">NIP.
                        {{ $lpj->nip_kepala_sekolah ?? $schoolSettings['nip_kepala_sekolah'] }}</div>
                @endif
            </div>
            <div class="signature-box">
                <div class="signature-date">
                    {{ $lpj->tempat_ttd ?? ($schoolSettings['kabupaten'] ?? 'Kota') }},
                    {{ $lpj->tanggal_surat ? $lpj->tanggal_surat->translatedFormat('d F Y') : now()->translatedFormat('d F Y') }}
                </div>
                <div class="signature-title">Ketua Panitia,</div>
                <div class="signature-name">
                    {{ $lpj->nama_ketua ?? '.............................' }}</div>
                @if ($lpj->nip_ketua)
                    <div class="signature-nip">NIP. {{ $lpj->nip_ketua }}</div>
                @endif
            </div>
        </div>
    </div>
</body>

</html>
