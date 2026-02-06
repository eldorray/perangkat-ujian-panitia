<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('school_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Insert default values
        $defaults = [
            'nama_sekolah' => 'SMA Negeri 1 Contoh',
            'npsn' => '12345678',
            'alamat' => 'Jl. Pendidikan No. 1',
            'kelurahan' => 'Kelurahan',
            'kecamatan' => 'Kecamatan',
            'kabupaten' => 'Kabupaten',
            'provinsi' => 'Provinsi',
            'kode_pos' => '12345',
            'telepon' => '(021) 1234567',
            'email' => 'info@smacontoh.sch.id',
            'website' => 'https://smacontoh.sch.id',
            'kepala_sekolah' => 'Dr. Budi Santoso, M.Pd',
            'nip_kepala_sekolah' => '197001011990031001',
            'logo' => null,
        ];

        foreach ($defaults as $key => $value) {
            \Illuminate\Support\Facades\DB::table('school_settings')->insert([
                'key' => $key,
                'value' => $value,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('school_settings');
    }
};
