<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ $pos->judul }}</title>
    <style>
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
        }

        .page {
            padding: 20px 40px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .header h2 {
            font-size: 13pt;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .header .school-name {
            font-size: 12pt;
            font-weight: bold;
        }

        .header .year {
            font-size: 11pt;
        }

        .toc {
            margin: 30px 0;
            page-break-after: always;
        }

        .toc h2 {
            text-align: center;
            font-size: 14pt;
            margin-bottom: 20px;
        }

        .toc-item {
            padding: 5px 0;
            font-size: 11pt;
        }

        .chapter {
            margin-top: 20px;
            margin-bottom: 15px;
        }

        .chapter-title {
            font-size: 13pt;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .section-title {
            font-size: 12pt;
            font-weight: bold;
            margin-top: 15px;
            margin-bottom: 10px;
        }

        .content {
            text-align: justify;
            margin-bottom: 10px;
        }

        .content p {
            margin-bottom: 8px;
            text-indent: 40px;
        }

        .list-content {
            margin-left: 20px;
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>

<body>
    <div class="page">
        <!-- Header -->
        <div class="header">
            <h1>{{ $pos->judul }}</h1>
            <h2>{{ $kegiatanUjian->nama_ujian }}</h2>
            <div class="school-name">{{ $namaSekolah }}</div>
            <div class="year">Tahun Pelajaran {{ $kegiatanUjian->tahunAjaran->nama }}
                {{ $kegiatanUjian->tahunAjaran->semester }}</div>
        </div>

        <!-- Daftar Isi -->
        <div class="toc">
            <h2>DAFTAR ISI</h2>
            <div class="toc-item">BAB I - PENDAHULUAN</div>
            <div class="toc-item">BAB II - PENGERTIAN ISTILAH</div>
            <div class="toc-item">BAB III - PESERTA UJIAN</div>
            <div class="toc-item">BAB IV - PENYELENGGARA UJIAN</div>
            <div class="toc-item">BAB V - MATERI DAN BENTUK SOAL</div>
            <div class="toc-item">BAB VI - PELAKSANAAN UJIAN</div>
            <div class="toc-item">BAB VII - DOKUMEN UJIAN</div>
            <div class="toc-item">BAB VIII - PENILAIAN DAN KELULUSAN</div>
            <div class="toc-item">BAB IX - PEMBIAYAAN</div>
            <div class="toc-item">BAB X - SANKSI</div>
            <div class="toc-item">BAB XI - PENUTUP</div>
        </div>

        <!-- BAB I - PENDAHULUAN -->
        <div class="chapter page-break">
            <div class="chapter-title">BAB I - PENDAHULUAN</div>

            @if ($pos->latar_belakang)
                <div class="section-title">A. Latar Belakang</div>
                <div class="content">{!! nl2br(e($pos->latar_belakang)) !!}</div>
            @endif

            @if ($pos->dasar_hukum)
                <div class="section-title">B. Dasar Hukum</div>
                <div class="content">{!! nl2br(e($pos->dasar_hukum)) !!}</div>
            @endif

            @if ($pos->tujuan)
                <div class="section-title">C. Tujuan</div>
                <div class="content">{!! nl2br(e($pos->tujuan)) !!}</div>
            @endif

            @if ($pos->ruang_lingkup)
                <div class="section-title">D. Ruang Lingkup</div>
                <div class="content">{!! nl2br(e($pos->ruang_lingkup)) !!}</div>
            @endif
        </div>

        <!-- BAB II - PENGERTIAN ISTILAH -->
        @if ($pos->pengertian_istilah)
            <div class="chapter">
                <div class="chapter-title">BAB II - PENGERTIAN ISTILAH</div>
                <div class="content">{!! nl2br(e($pos->pengertian_istilah)) !!}</div>
            </div>
        @endif

        <!-- BAB III - PESERTA UJIAN -->
        @if ($pos->persyaratan_peserta)
            <div class="chapter">
                <div class="chapter-title">BAB III - PESERTA UJIAN</div>
                <div class="content">{!! nl2br(e($pos->persyaratan_peserta)) !!}</div>
            </div>
        @endif

        <!-- BAB IV - PENYELENGGARA UJIAN -->
        @if ($pos->penyelenggara_ujian || $pos->tugas_kewenangan)
            <div class="chapter">
                <div class="chapter-title">BAB IV - PENYELENGGARA UJIAN</div>

                @if ($pos->penyelenggara_ujian)
                    <div class="section-title">A. Penyelenggara Ujian</div>
                    <div class="content">{!! nl2br(e($pos->penyelenggara_ujian)) !!}</div>
                @endif

                @if ($pos->tugas_kewenangan)
                    <div class="section-title">B. Tugas dan Kewenangan</div>
                    <div class="content">{!! nl2br(e($pos->tugas_kewenangan)) !!}</div>
                @endif
            </div>
        @endif

        <!-- BAB V - MATERI DAN BENTUK SOAL -->
        @if ($pos->materi_ujian || $pos->bentuk_soal || $pos->kisi_kisi)
            <div class="chapter">
                <div class="chapter-title">BAB V - MATERI DAN BENTUK SOAL</div>

                @if ($pos->materi_ujian)
                    <div class="section-title">A. Materi Ujian</div>
                    <div class="content">{!! nl2br(e($pos->materi_ujian)) !!}</div>
                @endif

                @if ($pos->bentuk_soal)
                    <div class="section-title">B. Bentuk Soal</div>
                    <div class="content">{!! nl2br(e($pos->bentuk_soal)) !!}</div>
                @endif

                @if ($pos->kisi_kisi)
                    <div class="section-title">C. Kisi-kisi</div>
                    <div class="content">{!! nl2br(e($pos->kisi_kisi)) !!}</div>
                @endif
            </div>
        @endif

        <!-- BAB VI - PELAKSANAAN UJIAN -->
        <div class="chapter">
            <div class="chapter-title">BAB VI - PELAKSANAAN UJIAN</div>

            @if ($pos->jadwal_pelaksanaan)
                <div class="section-title">A. Jadwal Pelaksanaan</div>
                <div class="content">{!! nl2br(e($pos->jadwal_pelaksanaan)) !!}</div>
            @endif

            @if ($pos->pengaturan_ruang)
                <div class="section-title">B. Pengaturan Ruang</div>
                <div class="content">{!! nl2br(e($pos->pengaturan_ruang)) !!}</div>
            @endif

            @if ($pos->tata_tertib_peserta)
                <div class="section-title">C. Tata Tertib Peserta</div>
                <div class="content">{!! nl2br(e($pos->tata_tertib_peserta)) !!}</div>
            @endif

            @if ($pos->tata_tertib_pengawas)
                <div class="section-title">D. Tata Tertib Pengawas</div>
                <div class="content">{!! nl2br(e($pos->tata_tertib_pengawas)) !!}</div>
            @endif

            @if ($pos->prosedur_pelaksanaan)
                <div class="section-title">E. Prosedur Pelaksanaan</div>
                <div class="content">{!! nl2br(e($pos->prosedur_pelaksanaan)) !!}</div>
            @endif
        </div>

        <!-- BAB VII - DOKUMEN UJIAN -->
        @if ($pos->dokumen_ujian)
            <div class="chapter">
                <div class="chapter-title">BAB VII - DOKUMEN UJIAN</div>
                <div class="content">{!! nl2br(e($pos->dokumen_ujian)) !!}</div>
            </div>
        @endif

        <!-- BAB VIII - PENILAIAN DAN KELULUSAN -->
        @if ($pos->kriteria_penilaian || $pos->kriteria_kelulusan)
            <div class="chapter">
                <div class="chapter-title">BAB VIII - PENILAIAN DAN KELULUSAN</div>

                @if ($pos->kriteria_penilaian)
                    <div class="section-title">A. Kriteria Penilaian</div>
                    <div class="content">{!! nl2br(e($pos->kriteria_penilaian)) !!}</div>
                @endif

                @if ($pos->kriteria_kelulusan)
                    <div class="section-title">B. Kriteria Kelulusan</div>
                    <div class="content">{!! nl2br(e($pos->kriteria_kelulusan)) !!}</div>
                @endif
            </div>
        @endif

        <!-- BAB IX - PEMBIAYAAN -->
        @if ($pos->pembiayaan)
            <div class="chapter">
                <div class="chapter-title">BAB IX - PEMBIAYAAN</div>
                <div class="content">{!! nl2br(e($pos->pembiayaan)) !!}</div>
            </div>
        @endif

        <!-- BAB X - SANKSI -->
        @if ($pos->sanksi_pelanggaran)
            <div class="chapter">
                <div class="chapter-title">BAB X - SANKSI</div>
                <div class="content">{!! nl2br(e($pos->sanksi_pelanggaran)) !!}</div>
            </div>
        @endif

        <!-- BAB XI - PENUTUP -->
        @if ($pos->penutup)
            <div class="chapter">
                <div class="chapter-title">BAB XI - PENUTUP</div>
                <div class="content">{!! nl2br(e($pos->penutup)) !!}</div>
            </div>
        @endif
    </div>
</body>

</html>
