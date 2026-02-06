<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelajaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_mapel',
        'nama_mapel',
        'jam_per_minggu',
        'kelompok',
        'jurusan',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'jam_per_minggu' => 'integer',
    ];

    // Scope for active pelajaran
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Accessor for kelompok label
    public function getKelompokLabelAttribute(): string
    {
        return match($this->kelompok) {
            'PAI' => 'Pendidikan Agama Islam',
            'Umum' => 'Umum',
            default => $this->kelompok,
        };
    }
}
