<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenugasanPengawas extends Model
{
    protected $table = 'penugasan_pengawas';

    protected $fillable = [
        'kegiatan_ujian_id',
        'selected_pengawas',
        'assignments',
    ];

    protected $casts = [
        'selected_pengawas' => 'array',
        'assignments' => 'array',
    ];

    public function kegiatanUjian(): BelongsTo
    {
        return $this->belongsTo(KegiatanUjian::class);
    }
}
