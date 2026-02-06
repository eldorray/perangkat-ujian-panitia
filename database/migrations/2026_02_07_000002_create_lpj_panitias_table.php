<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lpj_panitias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kegiatan_ujian_id')->constrained('kegiatan_ujians')->cascadeOnDelete();
            $table->string('nomor_surat')->nullable();
            $table->date('tanggal_surat')->nullable();
            $table->text('pendahuluan')->nullable();
            $table->text('dasar_pelaksanaan')->nullable(); // JSON array
            $table->text('tujuan')->nullable(); // JSON array
            $table->text('waktu_tempat')->nullable();
            $table->text('susunan_panitia_text')->nullable(); // optional note
            $table->text('pelaksanaan_kegiatan')->nullable(); // JSON array
            $table->text('hasil_pelaksanaan')->nullable(); // JSON array
            $table->text('kendala_hambatan')->nullable(); // JSON array
            $table->text('kesimpulan')->nullable();
            $table->text('saran')->nullable(); // JSON array
            $table->text('penutup')->nullable();
            $table->string('tempat_ttd')->nullable();
            $table->string('nama_ketua')->nullable();
            $table->string('nip_ketua')->nullable();
            $table->string('nama_kepala_sekolah')->nullable();
            $table->string('nip_kepala_sekolah')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lpj_panitias');
    }
};
