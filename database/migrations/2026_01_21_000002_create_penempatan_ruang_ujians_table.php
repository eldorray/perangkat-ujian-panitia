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
        Schema::create('penempatan_ruang_ujians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kegiatan_ujian_id')->constrained('kegiatan_ujians')->cascadeOnDelete();
            $table->foreignId('pasangan_kelas_ujian_id')->constrained('pasangan_kelas_ujians')->cascadeOnDelete();
            $table->foreignId('ruang_ujian_id')->constrained('ruang_ujians')->cascadeOnDelete();
            $table->foreignId('siswa_id')->constrained('siswas')->cascadeOnDelete();
            $table->integer('nomor_urut'); // Nomor urut dalam ruangan
            $table->string('asal_kelas'); // Kelas asal siswa (untuk tracking)
            $table->timestamps();
            
            // Unique constraint: satu siswa hanya di satu ruang per kegiatan
            $table->unique(['kegiatan_ujian_id', 'siswa_id'], 'unique_siswa_per_kegiatan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penempatan_ruang_ujians');
    }
};
