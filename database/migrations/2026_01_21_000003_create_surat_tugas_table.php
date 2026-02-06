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
        Schema::create('surat_tugas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kegiatan_ujian_id')->constrained('kegiatan_ujians')->cascadeOnDelete();
            $table->enum('jenis', ['mengawas', 'mengoreksi']);
            $table->string('nomor_surat');
            $table->date('tanggal_surat');
            $table->text('dasar_surat')->nullable();
            $table->text('untuk_keperluan');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_tugas');
    }
};
