<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $fillable = [
        'nama',
        'tingkat',
    ];

    /**
     * Count siswa in this class by matching tingkat_rombel pattern
     * Since siswas table uses tingkat_rombel string instead of kelas_id foreign key
     */
    public function getSiswasCountAttribute(): int
    {
        return Siswa::where('tingkat_rombel', 'like', "%{$this->nama}%")->count();
    }

    /**
     * Get display name with tingkat
     */
    public function getDisplayNameAttribute(): string
    {
        return "{$this->tingkat} - {$this->nama}";
    }
}
