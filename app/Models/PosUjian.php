<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PosUjian extends Model
{
    protected $table = 'pos_ujians';

    protected $fillable = [
        'kegiatan_ujian_id',
        'judul',
        'nomor_dokumen',
        // BAB I - Pendahuluan
        'latar_belakang',
        'dasar_hukum',
        'tujuan',
        'ruang_lingkup',
        // BAB II - Pengertian Istilah
        'pengertian_istilah',
        // BAB III - Peserta Ujian
        'persyaratan_peserta',
        // BAB IV - Penyelenggara
        'penyelenggara_ujian',
        'tugas_kewenangan',
        // BAB V - Materi dan Bentuk Soal
        'materi_ujian',
        'bentuk_soal',
        'kisi_kisi',
        // BAB VI - Pelaksanaan Ujian
        'jadwal_pelaksanaan',
        'pengaturan_ruang',
        'tata_tertib_peserta',
        'tata_tertib_pengawas',
        'prosedur_pelaksanaan',
        // BAB VII - Dokumen Ujian
        'dokumen_ujian',
        // BAB VIII - Penilaian dan Kelulusan
        'kriteria_penilaian',
        'kriteria_kelulusan',
        // BAB IX - Pembiayaan
        'pembiayaan',
        // BAB X - Sanksi
        'sanksi_pelanggaran',
        // BAB XI - Penutup
        'penutup',
    ];

    public function kegiatanUjian(): BelongsTo
    {
        return $this->belongsTo(KegiatanUjian::class);
    }

    /**
     * Get sections for form display
     */
    public static function getSections(): array
    {
        return [
            'pendahuluan' => [
                'title' => 'BAB I - PENDAHULUAN',
                'fields' => [
                    'latar_belakang' => 'Latar Belakang',
                    'dasar_hukum' => 'Dasar Hukum',
                    'tujuan' => 'Tujuan',
                    'ruang_lingkup' => 'Ruang Lingkup',
                ]
            ],
            'pengertian' => [
                'title' => 'BAB II - PENGERTIAN ISTILAH',
                'fields' => [
                    'pengertian_istilah' => 'Pengertian Istilah',
                ]
            ],
            'peserta' => [
                'title' => 'BAB III - PESERTA UJIAN',
                'fields' => [
                    'persyaratan_peserta' => 'Persyaratan Peserta',
                ]
            ],
            'penyelenggara' => [
                'title' => 'BAB IV - PENYELENGGARA UJIAN',
                'fields' => [
                    'penyelenggara_ujian' => 'Penyelenggara Ujian',
                    'tugas_kewenangan' => 'Tugas dan Kewenangan',
                ]
            ],
            'materi' => [
                'title' => 'BAB V - MATERI DAN BENTUK SOAL',
                'fields' => [
                    'materi_ujian' => 'Materi Ujian',
                    'bentuk_soal' => 'Bentuk Soal',
                    'kisi_kisi' => 'Kisi-kisi',
                ]
            ],
            'pelaksanaan' => [
                'title' => 'BAB VI - PELAKSANAAN UJIAN',
                'fields' => [
                    'jadwal_pelaksanaan' => 'Jadwal Pelaksanaan',
                    'pengaturan_ruang' => 'Pengaturan Ruang',
                    'tata_tertib_peserta' => 'Tata Tertib Peserta',
                    'tata_tertib_pengawas' => 'Tata Tertib Pengawas',
                    'prosedur_pelaksanaan' => 'Prosedur Pelaksanaan',
                ]
            ],
            'dokumen' => [
                'title' => 'BAB VII - DOKUMEN UJIAN',
                'fields' => [
                    'dokumen_ujian' => 'Dokumen Ujian',
                ]
            ],
            'penilaian' => [
                'title' => 'BAB VIII - PENILAIAN DAN KELULUSAN',
                'fields' => [
                    'kriteria_penilaian' => 'Kriteria Penilaian',
                    'kriteria_kelulusan' => 'Kriteria Kelulusan',
                ]
            ],
            'pembiayaan' => [
                'title' => 'BAB IX - PEMBIAYAAN',
                'fields' => [
                    'pembiayaan' => 'Pembiayaan',
                ]
            ],
            'sanksi' => [
                'title' => 'BAB X - SANKSI',
                'fields' => [
                    'sanksi_pelanggaran' => 'Sanksi Pelanggaran',
                ]
            ],
            'penutup' => [
                'title' => 'BAB XI - PENUTUP',
                'fields' => [
                    'penutup' => 'Penutup',
                ]
            ],
        ];
    }
}
