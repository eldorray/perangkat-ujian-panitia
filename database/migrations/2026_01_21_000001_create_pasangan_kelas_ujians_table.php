<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pasangan_kelas_ujians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kegiatan_ujian_id')->constrained('kegiatan_ujians')->cascadeOnDelete();
            $table->string('kelas_a_nama'); // e.g., "1A"
            $table->string('kelas_b_nama'); // e.g., "1B"
            $table->timestamps();
            
            // Unique constraint: satu pasangan kelas hanya sekali per kegiatan
            $table->unique(['kegiatan_ujian_id', 'kelas_a_nama', 'kelas_b_nama'], 'unique_pasangan_kelas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pasangan_kelas_ujians');
    }
};
