<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pos_ujians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kegiatan_ujian_id')->constrained('kegiatan_ujians')->onDelete('cascade');
            
            // Header
            $table->string('judul')->default('Prosedur Operasional Standar (POS) Ujian Sekolah');
            $table->string('nomor_dokumen')->nullable();
            
            // BAB I - PENDAHULUAN
            $table->text('latar_belakang')->nullable();
            $table->text('dasar_hukum')->nullable();
            $table->text('tujuan')->nullable();
            $table->text('ruang_lingkup')->nullable();
            
            // BAB II - PENGERTIAN ISTILAH
            $table->text('pengertian_istilah')->nullable();
            
            // BAB III - PESERTA UJIAN
            $table->text('persyaratan_peserta')->nullable();
            
            // BAB IV - PENYELENGGARA
            $table->text('penyelenggara_ujian')->nullable();
            $table->text('tugas_kewenangan')->nullable();
            
            // BAB V - MATERI DAN BENTUK SOAL
            $table->text('materi_ujian')->nullable();
            $table->text('bentuk_soal')->nullable();
            $table->text('kisi_kisi')->nullable();
            
            // BAB VI - PELAKSANAAN UJIAN
            $table->text('jadwal_pelaksanaan')->nullable();
            $table->text('pengaturan_ruang')->nullable();
            $table->text('tata_tertib_peserta')->nullable();
            $table->text('tata_tertib_pengawas')->nullable();
            $table->text('prosedur_pelaksanaan')->nullable();
            
            // BAB VII - DOKUMEN UJIAN
            $table->text('dokumen_ujian')->nullable();
            
            // BAB VIII - PENILAIAN DAN KELULUSAN
            $table->text('kriteria_penilaian')->nullable();
            $table->text('kriteria_kelulusan')->nullable();
            
            // BAB IX - PEMBIAYAAN
            $table->text('pembiayaan')->nullable();
            
            // BAB X - SANKSI
            $table->text('sanksi_pelanggaran')->nullable();
            
            // BAB XI - PENUTUP
            $table->text('penutup')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pos_ujians');
    }
};
