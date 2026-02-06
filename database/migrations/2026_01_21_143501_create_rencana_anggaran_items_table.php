<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rencana_anggaran_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rencana_anggaran_id')->constrained()->cascadeOnDelete();
            
            // Kategori: pengeluaran, insentif_panitia, insentif_struktural, operasional
            $table->string('kategori');
            $table->string('nama_item');
            $table->integer('jumlah')->nullable();
            $table->string('operator')->nullable(); // 'x' or 'X'
            $table->integer('jumlah2')->nullable(); // second quantity if applicable
            $table->string('operator2')->nullable();
            $table->integer('jumlah3')->nullable(); // third quantity if applicable  
            $table->decimal('harga_satuan', 15, 2)->nullable();
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->integer('sort_order')->default(0);
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rencana_anggaran_items');
    }
};
