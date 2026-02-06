<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RencanaAnggaran extends Model
{
    protected $table = 'rencana_anggarans';

    protected $fillable = [
        'kegiatan_ujian_id',
        'sumber_anggaran',
        'jumlah_siswa',
        'label_siswa_non_k',
        'iuran_siswa_non_k',
        'jumlah_siswa_non_k',
        'label_siswa_k',
        'jumlah_siswa_k',
        'iuran_siswa_k',
        'total_pemasukan',
        'tempat',
        'tanggal_dokumen',
        'nama_ketua',
        'nip_ketua',
        'nama_bendahara',
        'nip_bendahara',
        'nama_kepala_sekolah',
        'nip_kepala_sekolah',
    ];

    protected $casts = [
        'tanggal_dokumen' => 'date',
        'iuran_siswa_non_k' => 'decimal:2',
        'iuran_siswa_k' => 'decimal:2',
        'total_pemasukan' => 'decimal:2',
    ];

    public function kegiatanUjian(): BelongsTo
    {
        return $this->belongsTo(KegiatanUjian::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(RencanaAnggaranItem::class)->orderBy('sort_order');
    }

    public function pengeluaranItems(): HasMany
    {
        return $this->hasMany(RencanaAnggaranItem::class)
            ->where('kategori', 'pengeluaran')
            ->orderBy('sort_order');
    }

    public function insentifPanitiaItems(): HasMany
    {
        return $this->hasMany(RencanaAnggaranItem::class)
            ->where('kategori', 'insentif_panitia')
            ->orderBy('sort_order');
    }

    public function insentifStrukturalItems(): HasMany
    {
        return $this->hasMany(RencanaAnggaranItem::class)
            ->where('kategori', 'insentif_struktural')
            ->orderBy('sort_order');
    }

    public function operasionalItems(): HasMany
    {
        return $this->hasMany(RencanaAnggaranItem::class)
            ->where('kategori', 'operasional')
            ->orderBy('sort_order');
    }

    public function getSubtotalPemasukanNonKAttribute(): float
    {
        return $this->jumlah_siswa_non_k * $this->iuran_siswa_non_k;
    }

    public function getSubtotalPemasukanKAttribute(): float
    {
        return $this->jumlah_siswa_k * $this->iuran_siswa_k;
    }

    public function getTotalPengeluaranAttribute(): float
    {
        return $this->items()->sum('subtotal');
    }

    public function getSaldoAttribute(): float
    {
        return $this->total_pemasukan - $this->total_pengeluaran;
    }

    public function calculateTotalPemasukan(): void
    {
        $this->total_pemasukan = $this->subtotal_pemasukan_non_k + $this->subtotal_pemasukan_k;
        $this->save();
    }
}
