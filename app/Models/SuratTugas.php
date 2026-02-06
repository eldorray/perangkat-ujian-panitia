<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SuratTugas extends Model
{
    protected $table = 'surat_tugas';

    protected $fillable = [
        'kegiatan_ujian_id',
        'jenis',
        'nomor_surat',
        'tanggal_surat',
        'dasar_surat',
        'untuk_keperluan',
        'tanggal_mulai',
        'tanggal_selesai',
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    /**
     * Get the kegiatan ujian
     */
    public function kegiatanUjian(): BelongsTo
    {
        return $this->belongsTo(KegiatanUjian::class);
    }

    /**
     * Get the gurus assigned to this surat tugas
     */
    public function gurus(): BelongsToMany
    {
        return $this->belongsToMany(Guru::class, 'surat_tugas_gurus')
            ->withPivot('tugas_tambahan')
            ->withTimestamps();
    }

    /**
     * Get jenis label
     */
    public function getJenisLabelAttribute(): string
    {
        return match($this->jenis) {
            'mengawas' => 'Mengawas Ujian',
            'mengoreksi' => 'Mengoreksi Ujian',
            default => $this->jenis,
        };
    }
}
