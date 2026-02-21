<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kegiatan_ujians', function (Blueprint $table) {
            $table->date('tanggal_dokumen')->nullable()->after('nip_ketua_panitia');
        });
    }

    public function down(): void
    {
        Schema::table('kegiatan_ujians', function (Blueprint $table) {
            $table->dropColumn('tanggal_dokumen');
        });
    }
};
