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
        Schema::table('penempatan_ruang_ujians', function (Blueprint $table) {
            // Make pasangan_kelas_ujian_id nullable to support per-class placement
            $table->foreignId('pasangan_kelas_ujian_id')->nullable()->change();
            
            // Add kelas_nama for per-class placement tracking
            $table->string('kelas_nama')->nullable()->after('asal_kelas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penempatan_ruang_ujians', function (Blueprint $table) {
            $table->dropColumn('kelas_nama');
            $table->foreignId('pasangan_kelas_ujian_id')->nullable(false)->change();
        });
    }
};
