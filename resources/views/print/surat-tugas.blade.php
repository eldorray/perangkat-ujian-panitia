<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Tugas - {{ $suratTugas->nomor_surat }}</title>
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
            line-height: 1.6;
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

        /* Judul Surat */
        .surat-title {
            text-align: center;
            margin-bottom: 30px;
        }

        .surat-title h1 {
            font-size: 14pt;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 5px;
        }

        .surat-title .nomor {
            font-size: 12pt;
            font-weight: bold;
        }

        /* Content */
        .surat-content {
            margin-bottom: 20px;
        }

        .section-title {
            margin-bottom: 10px;
        }

        .info-table {
            margin-left: 0;
            margin-bottom: 20px;
        }

        .info-table tr td {
            padding: 3px 0;
            vertical-align: top;
        }

        .info-table .label {
            width: 150px;
        }

        .info-table .separator {
            width: 20px;
            text-align: center;
        }

        .keperluan {
            text-align: justify;
            margin: 25px 0;
        }

        .closing {
            text-align: justify;
            margin-top: 25px;
        }

        /* Tanda Tangan */
        .signature {
            margin-top: 40px;
            display: flex;
            justify-content: flex-end;
        }

        .signature-box {
            text-align: left;
            width: 280px;
        }

        .signature-date {
            margin-bottom: 5px;
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

    @foreach ($suratTugas->gurus as $guru)
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
                <h1>SURAT TUGAS</h1>
                <div class="nomor">NOMOR : {{ $suratTugas->nomor_surat }}</div>
            </div>

            <!-- Content -->
            <div class="surat-content">
                <div class="section-title">Yang bertanda tangan di bawah ini :</div>
                <table class="info-table">
                    <tr>
                        <td class="label">N a m a</td>
                        <td class="separator">:</td>
                        <td><strong>{{ $schoolSettings['kepala_sekolah'] ?? '-' }}</strong></td>
                    </tr>
                    <tr>
                        <td class="label">Jabatan</td>
                        <td class="separator">:</td>
                        <td>Kepala {{ $schoolSettings['nama_sekolah'] ?? 'Sekolah' }}</td>
                    </tr>
                </table>

                <div class="section-title">Memberikan tugas kepada :</div>
                <table class="info-table">
                    <tr>
                        <td class="label">N a m a</td>
                        <td class="separator">:</td>
                        <td><strong>{{ $guru->full_name_with_titles }}</strong></td>
                    </tr>
                    <tr>
                        <td class="label">Tempat Tgl Lahir</td>
                        <td class="separator">:</td>
                        <td>{{ $guru->pob ?? '-' }}, {{ $guru->dob ? $guru->dob->translatedFormat('d F Y') : '-' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="label">NIP / NUPTK</td>
                        <td class="separator">:</td>
                        <td>{{ $guru->nip ?? '-' }} / {{ $guru->nuptk ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Jabatan</td>
                        <td class="separator">:</td>
                        <td>Guru</td>
                    </tr>
                </table>

                <div class="keperluan">
                    {{ $suratTugas->untuk_keperluan }}, dari tanggal
                    {{ $suratTugas->tanggal_mulai->translatedFormat('d') }} s.d
                    {{ $suratTugas->tanggal_selesai->translatedFormat('d F Y') }}.
                </div>

                <div class="closing">
                    Demikian Surat Tugas ini dibuat untuk dilaksanakan sebagaimana mestinya.
                </div>
            </div>

            <!-- Tanda Tangan -->
            <div class="signature">
                <div class="signature-box">
                    <div class="signature-date">
                        {{ $schoolSettings['kabupaten'] ?? 'Kota' }},
                        {{ $suratTugas->tanggal_surat->translatedFormat('d F Y') }}
                    </div>
                    <div class="signature-title">Kepala {{ $schoolSettings['nama_sekolah'] ?? 'Sekolah' }},</div>
                    <div class="signature-name">
                        {{ $schoolSettings['kepala_sekolah'] ?? '.............................' }}</div>
                    @if (!empty($schoolSettings['nip_kepala_sekolah']))
                        <div class="signature-nip">NIP. {{ $schoolSettings['nip_kepala_sekolah'] }}</div>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
</body>

</html>
