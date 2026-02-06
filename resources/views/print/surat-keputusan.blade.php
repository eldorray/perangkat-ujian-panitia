<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Keputusan - {{ $suratKeputusan->nomor_surat }}</title>
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
            font-size: 12pt;
            line-height: 1.5;
            color: #000;
            background: #fff;
        }

        .page {
            max-width: 210mm;
            min-height: 270mm;
            margin: 0 auto;
            padding: 5mm 10mm;
            page-break-after: always;
        }

        .page:last-child {
            page-break-after: avoid;
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

        /* Judul Surat */
        .surat-title {
            text-align: center;
            margin-bottom: 5px;
        }

        .surat-title h1 {
            font-size: 14pt;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 3px;
        }

        .surat-title .nomor {
            font-size: 12pt;
            font-weight: bold;
            margin-bottom: 3px;
        }

        .surat-title .tentang {
            font-size: 11pt;
            font-style: italic;
            margin-bottom: 15px;
        }

        /* Sections */
        .section {
            margin-bottom: 10px;
            display: flex;
        }

        .section-label {
            width: 120px;
            flex-shrink: 0;
            font-weight: normal;
        }

        .section-separator {
            width: 20px;
            flex-shrink: 0;
            text-align: center;
        }

        .section-content {
            flex: 1;
            text-align: justify;
        }

        .section-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .section-list li {
            margin-bottom: 3px;
            text-align: justify;
        }

        /* Memutuskan */
        .memutuskan-header {
            text-align: center;
            font-weight: bold;
            font-size: 13pt;
            margin: 15px 0 5px 0;
            letter-spacing: 3px;
        }

        .menetapkan-label {
            font-weight: bold;
            margin-bottom: 10px;
        }

        .keputusan-section {
            margin-bottom: 8px;
            display: flex;
        }

        .keputusan-label {
            width: 100px;
            flex-shrink: 0;
            font-weight: bold;
        }

        .keputusan-separator {
            width: 20px;
            flex-shrink: 0;
            text-align: center;
        }

        .keputusan-content {
            flex: 1;
            text-align: justify;
        }

        /* Tanda Tangan */
        .signature {
            margin-top: 30px;
            display: flex;
            justify-content: flex-end;
        }

        .signature-box {
            text-align: left;
            width: 280px;
        }

        .signature-date {
            margin-bottom: 3px;
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

        /* Lampiran */
        .lampiran-title {
            text-align: center;
            margin-bottom: 5px;
        }

        .lampiran-title .label {
            font-size: 11pt;
            margin-bottom: 2px;
        }

        .lampiran-title h2 {
            font-size: 14pt;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 3px;
        }

        .lampiran-title .sub {
            font-size: 12pt;
            font-weight: bold;
            margin-bottom: 3px;
        }

        .lampiran-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            margin-bottom: 20px;
        }

        .lampiran-table th,
        .lampiran-table td {
            border: 1px solid #000;
            padding: 6px 10px;
            font-size: 11pt;
        }

        .lampiran-table th {
            background: #f0f0f0;
            font-weight: bold;
            text-align: center;
        }

        .lampiran-table td.center {
            text-align: center;
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

    <!-- PAGE 1: Surat Keputusan -->
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

        <!-- Judul Surat -->
        <div class="surat-title">
            <h1>SURAT KEPUTUSAN</h1>
            <div class="nomor">KEPALA {{ strtoupper($schoolSettings['nama_sekolah'] ?? 'SEKOLAH') }}</div>
            <div class="nomor">NOMOR : {{ $suratKeputusan->nomor_surat }}</div>
            <div class="tentang">Tentang : {{ $suratKeputusan->tentang }}</div>
        </div>

        <!-- Menimbang -->
        @if (!empty($suratKeputusan->menimbang))
            <div class="section">
                <div class="section-label">Menimbang</div>
                <div class="section-separator">:</div>
                <div class="section-content">
                    <ol class="section-list" type="a">
                        @foreach ($suratKeputusan->menimbang as $index => $item)
                            <li>{{ chr(97 + $index) }}. {{ $item }}</li>
                        @endforeach
                    </ol>
                </div>
            </div>
        @endif

        <!-- Mengingat -->
        @if (!empty($suratKeputusan->mengingat))
            <div class="section">
                <div class="section-label">Mengingat</div>
                <div class="section-separator">:</div>
                <div class="section-content">
                    <ol class="section-list">
                        @foreach ($suratKeputusan->mengingat as $index => $item)
                            <li>{{ $index + 1 }}. {{ $item }}</li>
                        @endforeach
                    </ol>
                </div>
            </div>
        @endif

        <!-- Memperhatikan -->
        @if ($suratKeputusan->memperhatikan)
            <div class="section">
                <div class="section-label">Memperhatikan</div>
                <div class="section-separator">:</div>
                <div class="section-content">{{ $suratKeputusan->memperhatikan }}</div>
            </div>
        @endif

        <!-- MEMUTUSKAN -->
        <div class="memutuskan-header">MEMUTUSKAN</div>

        <div class="menetapkan-label">Menetapkan :</div>

        <!-- PERTAMA -->
        <div class="keputusan-section">
            <div class="keputusan-label">PERTAMA</div>
            <div class="keputusan-separator">:</div>
            <div class="keputusan-content">
                Menunjuk dan menetapkan nama-nama yang tercantum dalam lampiran Surat Keputusan ini sebagai
                Panitia Pelaksana {{ $kegiatanUjian->nama_ujian }}
                {{ $schoolSettings['nama_sekolah'] ?? '' }}
                Tahun Pelajaran {{ $kegiatanUjian->tahunAjaran->nama ?? '' }}.
            </div>
        </div>

        <!-- KEDUA -->
        <div class="keputusan-section">
            <div class="keputusan-label">KEDUA</div>
            <div class="keputusan-separator">:</div>
            <div class="keputusan-content">
                Panitia bertugas mempersiapkan, melaksanakan, dan melaporkan pelaksanaan
                {{ $kegiatanUjian->nama_ujian }}.
            </div>
        </div>

        <!-- KETIGA -->
        <div class="keputusan-section">
            <div class="keputusan-label">KETIGA</div>
            <div class="keputusan-separator">:</div>
            <div class="keputusan-content">
                Segala biaya yang timbul akibat pelaksanaan keputusan ini dibebankan pada anggaran yang tersedia.
            </div>
        </div>

        <!-- KEEMPAT -->
        <div class="keputusan-section">
            <div class="keputusan-label">KEEMPAT</div>
            <div class="keputusan-separator">:</div>
            <div class="keputusan-content">
                Surat Keputusan ini berlaku sejak tanggal ditetapkan, dengan ketentuan apabila terdapat kekeliruan
                akan diperbaiki sebagaimana mestinya.
            </div>
        </div>

        <!-- Tanda Tangan -->
        <div class="signature">
            <div class="signature-box">
                <div class="signature-date">
                    Ditetapkan di : {{ $suratKeputusan->ditetapkan_di ?? ($schoolSettings['kabupaten'] ?? 'Kota') }}
                </div>
                <div class="signature-date">
                    Pada tanggal : {{ $suratKeputusan->tanggal_surat->translatedFormat('d F Y') }}
                </div>
                <div class="signature-title" style="margin-top: 5px;">
                    Kepala {{ $schoolSettings['nama_sekolah'] ?? 'Sekolah' }},
                </div>
                <div class="signature-name">
                    {{ $schoolSettings['kepala_sekolah'] ?? '.............................' }}
                </div>
                @if (!empty($schoolSettings['nip_kepala_sekolah']))
                    <div class="signature-nip">NIP. {{ $schoolSettings['nip_kepala_sekolah'] }}</div>
                @endif
            </div>
        </div>
    </div>

    <!-- PAGE 2: Lampiran - Susunan Kepanitiaan -->
    <div class="page">
        <!-- Kop Surat (lampiran) -->
        <div class="lampiran-title">
            <div class="label">Lampiran 1 : Surat Keputusan Kepala {{ $schoolSettings['nama_sekolah'] ?? 'Sekolah' }}
            </div>
            <div class="label">Nomor : {{ $suratKeputusan->nomor_surat }}</div>
            <div class="label">Tanggal : {{ $suratKeputusan->tanggal_surat->translatedFormat('d F Y') }}</div>
            <div style="margin-top: 15px;"></div>
            <h2>SUSUNAN KEPANITIAAN</h2>
            <div class="sub">{{ strtoupper($kegiatanUjian->nama_ujian) }}</div>
            <div class="sub">{{ strtoupper($schoolSettings['nama_sekolah'] ?? '') }}</div>
            <div class="sub">TAHUN PELAJARAN {{ strtoupper($kegiatanUjian->tahunAjaran->nama ?? '') }}</div>
        </div>

        <table class="lampiran-table">
            <thead>
                <tr>
                    <th style="width: 40px;">No</th>
                    <th>Jabatan</th>
                    <th>Nama</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach ($suratKeputusan->panitia as $guru)
                    <tr>
                        <td class="center">{{ $no++ }}</td>
                        <td>{{ $guru->pivot->jabatan }}</td>
                        <td>{{ $guru->full_name_with_titles }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Tanda Tangan Lampiran -->
        <div class="signature">
            <div class="signature-box">
                <div class="signature-date">
                    {{ $suratKeputusan->ditetapkan_di ?? ($schoolSettings['kabupaten'] ?? 'Kota') }},
                    {{ $suratKeputusan->tanggal_surat->translatedFormat('d F Y') }}
                </div>
                <div class="signature-title" style="margin-top: 5px;">
                    Kepala {{ $schoolSettings['nama_sekolah'] ?? 'Sekolah' }},
                </div>
                <div class="signature-name">
                    {{ $schoolSettings['kepala_sekolah'] ?? '.............................' }}
                </div>
                @if (!empty($schoolSettings['nip_kepala_sekolah']))
                    <div class="signature-nip">NIP. {{ $schoolSettings['nip_kepala_sekolah'] }}</div>
                @endif
            </div>
        </div>
    </div>
</body>

</html>
