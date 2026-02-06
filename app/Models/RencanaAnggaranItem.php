<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RencanaAnggaranItem extends Model
{
    protected $table = 'rencana_anggaran_items';

    protected $fillable = [
        'rencana_anggaran_id',
        'kategori',
        'nama_item',
        'jumlah',
        'operator',
        'jumlah2',
        'operator2',
        'jumlah3',
        'harga_satuan',
        'subtotal',
        'sort_order',
    ];

    protected $casts = [
        'harga_satuan' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function rencanaAnggaran(): BelongsTo
    {
        return $this->belongsTo(RencanaAnggaran::class);
    }

    /**
     * Get formula display like "262 x 0 25000"
     */
    public function getFormulaAttribute(): string
    {
        $parts = [];
        
        if ($this->jumlah) {
            $parts[] = $this->jumlah;
        }
        
        if ($this->operator) {
            $parts[] = $this->operator;
        }
        
        if ($this->jumlah2 !== null) {
            $parts[] = $this->jumlah2;
        }
        
        if ($this->operator2) {
            $parts[] = $this->operator2;
        }
        
        if ($this->jumlah3 !== null) {
            $parts[] = $this->jumlah3;
        }
        
        if ($this->harga_satuan) {
            $parts[] = number_format($this->harga_satuan, 0, ',', '');
        }
        
        return implode(' ', $parts);
    }

    /**
     * Calculate subtotal based on formula
     */
    public function calculateSubtotal(): void
    {
        $result = 1;
        
        if ($this->jumlah) {
            $result = $this->jumlah;
        }
        
        if ($this->jumlah2 !== null) {
            $result *= $this->jumlah2;
        }
        
        if ($this->jumlah3 !== null) {
            $result *= $this->jumlah3;
        }
        
        if ($this->harga_satuan) {
            $result *= $this->harga_satuan;
        }
        
        $this->subtotal = $result;
    }
}
