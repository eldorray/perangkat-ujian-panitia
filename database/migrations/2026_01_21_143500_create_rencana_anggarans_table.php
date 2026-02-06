<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rencana_anggarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kegiatan_ujian_id')->constrained()->cascadeOnDelete();
            
            // Sumber Anggaran
            $table->text('sumber_anggaran')->nullable();
            
            // Pemasukan
            $table->integer('jumlah_siswa')->default(0);
            $table->string('label_siswa_non_k')->default('Pemasukan siswa non K');
            $table->decimal('iuran_siswa_non_k', 15, 2)->default(0);
            $table->integer('jumlah_siswa_non_k')->default(0);
            $table->string('label_siswa_k')->default('Pemasukan siswa');
            $table->integer('jumlah_siswa_k')->default(0);
            $table->decimal('iuran_siswa_k', 15, 2)->default(0);
            $table->decimal('total_pemasukan', 15, 2)->default(0);
            
            // Tanda Tangan
            $table->string('tempat')->nullable();
            $table->date('tanggal_dokumen')->nullable();
            $table->string('nama_ketua')->nullable();
            $table->string('nip_ketua')->nullable();
            $table->string('nama_bendahara')->nullable();
            $table->string('nip_bendahara')->nullable();
            $table->string('nama_kepala_sekolah')->nullable();
            $table->string('nip_kepala_sekolah')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rencana_anggarans');
    }
};
