<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kegiatan_ujians', function (Blueprint $table) {
            $table->boolean('is_locked')->default(false)->after('keterangan');
            $table->string('lock_pin', 255)->nullable()->after('is_locked');
            $table->timestamp('locked_at')->nullable()->after('lock_pin');
            $table->foreignId('locked_by')->nullable()->after('locked_at')
                ->constrained('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('kegiatan_ujians', function (Blueprint $table) {
            $table->dropForeign(['locked_by']);
            $table->dropColumn(['is_locked', 'lock_pin', 'locked_at', 'locked_by']);
        });
    }
};
