<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;

    protected $fillable = [
        'nip',
        'nuptk',
        'npk',
        'nik',
        'front_title',
        'full_name',
        'back_title',
        'gender',
        'pob',
        'dob',
        'phone_number',
        'address',
        'status_pegawai',
        'is_active',
        'sk_awal_path',
        'sk_akhir_path',
    ];

    protected $casts = [
        'dob' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Get full name with titles
     */
    public function getFullNameWithTitlesAttribute(): string
    {
        $name = '';
        if ($this->front_title) {
            $name .= $this->front_title . ' ';
        }
        $name .= $this->full_name;
        if ($this->back_title) {
            $name .= ', ' . $this->back_title;
        }
        return $name;
    }

    /**
     * Get jenis kelamin label
     */
    public function getGenderLabelAttribute(): string
    {
        return $this->gender === 'L' ? 'Laki-laki' : 'Perempuan';
    }

    /**
     * Get status color
     */
    public function getStatusColorAttribute(): string
    {
        return $this->is_active ? 'green' : 'red';
    }

    /**
     * Scope untuk mendapatkan guru aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
