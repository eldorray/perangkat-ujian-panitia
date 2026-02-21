<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kegiatan_ujians', function (Blueprint $table) {
            $table->string('ketua_panitia')->nullable()->after('keterangan');
            $table->string('nip_ketua_panitia')->nullable()->after('ketua_panitia');
        });
    }

    public function down(): void
    {
        Schema::table('kegiatan_ujians', function (Blueprint $table) {
            $table->dropColumn(['ketua_panitia', 'nip_ketua_panitia']);
        });
    }
};
