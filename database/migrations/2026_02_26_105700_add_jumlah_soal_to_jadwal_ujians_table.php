<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jadwal_ujians', function (Blueprint $table) {
            $table->integer('jumlah_soal')->nullable()->after('keterangan');
            $table->integer('jumlah_cadangan')->nullable()->after('jumlah_soal');
        });
    }

    public function down(): void
    {
        Schema::table('jadwal_ujians', function (Blueprint $table) {
            $table->dropColumn(['jumlah_soal', 'jumlah_cadangan']);
        });
    }
};
