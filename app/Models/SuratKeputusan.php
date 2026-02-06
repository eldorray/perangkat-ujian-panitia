<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SuratKeputusan extends Model
{
    protected $table = 'surat_keputusans';

    protected $fillable = [
        'kegiatan_ujian_id',
        'nomor_surat',
        'tanggal_surat',
        'tentang',
        'menimbang',
        'mengingat',
        'memperhatikan',
        'ditetapkan_di',
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
        'menimbang' => 'array',
        'mengingat' => 'array',
    ];

    public function kegiatanUjian(): BelongsTo
    {
        return $this->belongsTo(KegiatanUjian::class);
    }

    public function panitia(): BelongsToMany
    {
        return $this->belongsToMany(Guru::class, 'surat_keputusan_panitias')
            ->withPivot('jabatan', 'urutan')
            ->orderByPivot('urutan')
            ->withTimestamps();
    }

    /**
     * Get panitia grouped by jabatan
     */
    public function getPanitiaByJabatan(): array
    {
        $grouped = [];
        foreach ($this->panitia as $guru) {
            $jabatan = $guru->pivot->jabatan;
            if (!isset($grouped[$jabatan])) {
                $grouped[$jabatan] = [];
            }
            $grouped[$jabatan][] = $guru;
        }
        return $grouped;
    }

    /**
     * Default jabatan list
     */
    public static function jabatanList(): array
    {
        return [
            'Penanggung Jawab',
            'Ketua',
            'Sekretaris',
            'Bendahara',
            'Proktor',
            'Teknisi',
            'Anggota',
        ];
    }
}
