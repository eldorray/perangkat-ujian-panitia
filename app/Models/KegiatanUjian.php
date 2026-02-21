<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Hash;

class KegiatanUjian extends Model
{
    protected $table = 'kegiatan_ujians';

    protected $fillable = [
        'nama_ujian',
        'tahun_ajaran_id',
        'sk_number',
        'keterangan',
        'ketua_panitia',
        'nip_ketua_panitia',
        'is_locked',
        'lock_pin',
        'locked_at',
        'locked_by',
    ];

    protected $casts = [
        'is_locked' => 'boolean',
        'locked_at' => 'datetime',
    ];

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public function lockedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'locked_by');
    }

    public function jadwalUjians(): HasMany
    {
        return $this->hasMany(JadwalUjian::class)->orderBy('kelompok_kelas')->orderBy('tanggal')->orderBy('sort_order');
    }

    /**
     * Lock kegiatan with a 6-digit PIN
     */
    public function lock(string $pin): bool
    {
        $this->update([
            'is_locked' => true,
            'lock_pin' => Hash::make($pin),
            'locked_at' => now(),
            'locked_by' => auth()->id(),
        ]);

        return true;
    }

    /**
     * Unlock kegiatan if PIN is correct
     */
    public function unlock(string $pin): bool
    {
        if (!$this->verifyPin($pin)) {
            return false;
        }

        $this->update([
            'is_locked' => false,
            'lock_pin' => null,
            'locked_at' => null,
            'locked_by' => null,
        ]);

        return true;
    }

    /**
     * Verify if the given PIN matches the stored PIN
     */
    public function verifyPin(string $pin): bool
    {
        if (!$this->lock_pin) {
            return false;
        }

        return Hash::check($pin, $this->lock_pin);
    }
}
