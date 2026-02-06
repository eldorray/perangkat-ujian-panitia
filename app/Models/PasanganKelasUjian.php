<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PasanganKelasUjian extends Model
{
    protected $table = 'pasangan_kelas_ujians';

    protected $fillable = [
        'kegiatan_ujian_id',
        'kelas_a_nama',
        'kelas_b_nama',
    ];

    /**
     * Get the kegiatan ujian that owns this pasangan
     */
    public function kegiatanUjian(): BelongsTo
    {
        return $this->belongsTo(KegiatanUjian::class);
    }

    /**
     * Get all penempatan for this pasangan
     */
    public function penempatans(): HasMany
    {
        return $this->hasMany(PenempatanRuangUjian::class);
    }

    /**
     * Get display name for the pair
     */
    public function getDisplayNameAttribute(): string
    {
        return "{$this->kelas_a_nama} ↔ {$this->kelas_b_nama}";
    }

    /**
     * Get siswa from kelas A
     */
    public function getSiswaKelasA()
    {
        return Siswa::where('tingkat_rombel', 'like', "%{$this->kelas_a_nama}%")
            ->orderBy('nama_lengkap')
            ->get();
    }

    /**
     * Get siswa from kelas B
     */
    public function getSiswaKelasB()
    {
        return Siswa::where('tingkat_rombel', 'like', "%{$this->kelas_b_nama}%")
            ->orderBy('nama_lengkap')
            ->get();
    }
}
