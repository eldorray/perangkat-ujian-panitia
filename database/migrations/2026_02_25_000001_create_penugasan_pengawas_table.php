<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penugasan_pengawas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kegiatan_ujian_id')->constrained('kegiatan_ujians')->cascadeOnDelete();
            $table->json('selected_pengawas')->nullable(); // array of guru IDs
            $table->json('assignments')->nullable();       // {jadwal_id: {ruang_id: guru_code}}
            $table->timestamps();

            $table->unique('kegiatan_ujian_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penugasan_pengawas');
    }
};
