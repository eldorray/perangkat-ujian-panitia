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
            $table->string('nomor_peserta', 8)->nullable()->after('nomor_urut');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penempatan_ruang_ujians', function (Blueprint $table) {
            $table->dropColumn('nomor_peserta');
        });
    }
};
