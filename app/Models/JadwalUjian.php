<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JadwalUjian extends Model
{
    protected $table = 'jadwal_ujians';

    protected $fillable = [
        'kegiatan_ujian_id',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'mata_pelajaran',
        'kelompok_kelas',
        'keterangan',
        'sort_order',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    /**
     * Get the kegiatan ujian
     */
    public function kegiatanUjian(): BelongsTo
    {
        return $this->belongsTo(KegiatanUjian::class);
    }

    /**
     * Get formatted time range
     */
    public function getWaktuAttribute(): string
    {
        return substr($this->jam_mulai, 0, 5) . ' - ' . substr($this->jam_selesai, 0, 5);
    }

    /**
     * Get formatted date for display
     */
    public function getHariTanggalAttribute(): string
    {
        return $this->tanggal->translatedFormat('l, d F Y');
    }

    /**
     * Get kelompok kelas label for display
     */
    public function getKelompokKelasLabelAttribute(): string
    {
        return $this->kelompok_kelas ?: 'Semua Kelas';
    }

    /**
     * Roman numeral to Arabic number mapping
     */
    public static function romanToArabicMap(): array
    {
        return [
            'I' => '1', 'II' => '2', 'III' => '3',
            'IV' => '4', 'V' => '5', 'VI' => '6',
            'VII' => '7', 'VIII' => '8', 'IX' => '9',
            'X' => '10', 'XI' => '11', 'XII' => '12',
        ];
    }

    /**
     * Check if this jadwal applies to a given siswa based on their tingkat_rombel.
     * kelompok_kelas format: "Kelas I", "Kelas II", etc. (Roman numeral from Kelas.tingkat)
     * tingkat_rombel format: "Kelas 1 - KELAS 1B", "Kelas 2 - KELAS 2A", etc. (Arabic number)
     */
    public function matchesSiswa(string $tingkatRombel): bool
    {
        // No kelompok_kelas = applies to all
        if (!$this->kelompok_kelas) {
            return true;
        }

        // Extract tingkat from kelompok_kelas (e.g., "Kelas I" -> "I")
        $tingkat = str_replace('Kelas ', '', $this->kelompok_kelas);

        // Convert Roman to Arabic if applicable
        $map = self::romanToArabicMap();
        $arabicNum = $map[$tingkat] ?? $tingkat;

        // Extract the grade number from tingkat_rombel (e.g., "Kelas 1 - KELAS 1B" -> "1")
        if (preg_match('/Kelas\s+(\d+)/i', $tingkatRombel, $matches)) {
            return $matches[1] === $arabicNum;
        }

        // Fallback: direct string comparison
        return str_contains(strtolower($tingkatRombel), strtolower($tingkat))
            || str_contains(strtolower($tingkatRombel), strtolower($arabicNum));
    }
}
