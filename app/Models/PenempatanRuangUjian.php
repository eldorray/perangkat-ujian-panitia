<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenempatanRuangUjian extends Model
{
    protected $table = 'penempatan_ruang_ujians';

    protected $fillable = [
        'kegiatan_ujian_id',
        'pasangan_kelas_ujian_id',
        'ruang_ujian_id',
        'siswa_id',
        'nomor_urut',
        'nomor_peserta',
        'asal_kelas',
        'kelas_nama',
    ];

    /**
     * Get the kegiatan ujian
     */
    public function kegiatanUjian(): BelongsTo
    {
        return $this->belongsTo(KegiatanUjian::class);
    }

    /**
     * Get the pasangan kelas
     */
    public function pasanganKelas(): BelongsTo
    {
        return $this->belongsTo(PasanganKelasUjian::class, 'pasangan_kelas_ujian_id');
    }

    /**
     * Get the ruang ujian
     */
    public function ruangUjian(): BelongsTo
    {
        return $this->belongsTo(RuangUjian::class);
    }

    /**
     * Get the siswa
     */
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }
}
