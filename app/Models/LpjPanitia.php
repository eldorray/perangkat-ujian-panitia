<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LpjPanitia extends Model
{
    protected $table = 'lpj_panitias';

    protected $fillable = [
        'kegiatan_ujian_id',
        'nomor_surat',
        'tanggal_surat',
        'pendahuluan',
        'dasar_pelaksanaan',
        'tujuan',
        'waktu_tempat',
        'susunan_panitia_text',
        'pelaksanaan_kegiatan',
        'hasil_pelaksanaan',
        'kendala_hambatan',
        'kesimpulan',
        'saran',
        'penutup',
        'tempat_ttd',
        'nama_ketua',
        'nip_ketua',
        'nama_kepala_sekolah',
        'nip_kepala_sekolah',
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
        'dasar_pelaksanaan' => 'array',
        'tujuan' => 'array',
        'pelaksanaan_kegiatan' => 'array',
        'hasil_pelaksanaan' => 'array',
        'kendala_hambatan' => 'array',
        'saran' => 'array',
    ];

    public function kegiatanUjian(): BelongsTo
    {
        return $this->belongsTo(KegiatanUjian::class);
    }
}
