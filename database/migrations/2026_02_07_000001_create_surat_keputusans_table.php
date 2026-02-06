<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_keputusans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kegiatan_ujian_id')->constrained('kegiatan_ujians')->cascadeOnDelete();
            $table->string('nomor_surat');
            $table->date('tanggal_surat');
            $table->string('tentang');
            $table->text('menimbang')->nullable(); // JSON array of items
            $table->text('mengingat')->nullable(); // JSON array of items
            $table->text('memperhatikan')->nullable();
            $table->text('ditetapkan_di')->nullable();
            $table->timestamps();
        });

        Schema::create('surat_keputusan_panitias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_keputusan_id')->constrained('surat_keputusans')->cascadeOnDelete();
            $table->foreignId('guru_id')->constrained('gurus')->cascadeOnDelete();
            $table->string('jabatan'); // Penanggung Jawab, Ketua, Sekretaris, Bendahara, Proktor, Teknisi, Anggota
            $table->integer('urutan')->default(0);
            $table->timestamps();
            $table->unique(['surat_keputusan_id', 'guru_id', 'jabatan'], 'sk_panitia_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_keputusan_panitias');
        Schema::dropIfExists('surat_keputusans');
    }
};
