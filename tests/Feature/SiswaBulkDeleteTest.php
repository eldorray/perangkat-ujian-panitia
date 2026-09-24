<?php

namespace Tests\Feature;

use App\Livewire\Admin\SiswaManagement;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Tests\TestCase;

class SiswaBulkDeleteTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_delete_every_student_even_when_list_is_filtered_and_paginated(): void
    {
        $this->actingAs(User::factory()->create(['role' => User::ROLE_ADMIN]));

        $first = Siswa::create(['nama_lengkap' => 'Ali', 'status' => 'Aktif']);
        foreach (range(1, 11) as $number) {
            Siswa::create(['nama_lengkap' => "Budi {$number}", 'status' => 'Tidak Aktif']);
        }

        $this->get(route('admin.siswa'))->assertOk()->assertSee('Hapus Semua');

        Livewire::test(SiswaManagement::class)
            ->set('search', 'Ali')
            ->set('statusFilter', 'Aktif')
            ->call('confirmDeleteAll')
            ->assertSet('showDeleteAllModal', true)
            ->assertSee('seluruh 12 data siswa')
            ->set('deleteAllConfirmation', 'HAPUS SEMUA')
            ->call('deleteAll')
            ->assertHasNoErrors()
            ->assertSet('showDeleteAllModal', false)
            ->assertSee('12 data siswa berhasil dihapus.');

        $this->assertDatabaseCount('siswas', 0);
    }

    public function test_bulk_delete_requires_modal_and_exact_confirmation_and_can_be_cancelled(): void
    {
        $this->actingAs(User::factory()->create(['role' => User::ROLE_PANITIA]));
        Siswa::create(['nama_lengkap' => 'Ali']);

        Livewire::test(SiswaManagement::class)
            ->set('deleteAllConfirmation', 'HAPUS SEMUA')
            ->call('deleteAll')
            ->assertHasNoErrors()
            ->call('confirmDeleteAll')
            ->set('deleteAllConfirmation', 'hapus semua')
            ->call('deleteAll')
            ->assertHasErrors(['deleteAllConfirmation'])
            ->call('closeModal')
            ->assertSet('deleteAllConfirmation', '')
            ->assertSet('showDeleteAllModal', false);

        $this->assertDatabaseCount('siswas', 1);
    }

    public function test_bulk_delete_cascades_exam_placements_but_leaves_exam_data_intact(): void
    {
        $this->actingAs(User::factory()->create(['role' => User::ROLE_ADMIN]));
        $siswa = Siswa::create(['nama_lengkap' => 'Ali']);
        $now = now();
        $tahunId = DB::table('tahun_ajarans')->insertGetId(['nama' => '2026/2027', 'semester' => 'Ganjil', 'created_at' => $now, 'updated_at' => $now]);
        $kegiatanId = DB::table('kegiatan_ujians')->insertGetId(['nama_ujian' => 'PAS', 'tahun_ajaran_id' => $tahunId, 'created_at' => $now, 'updated_at' => $now]);
        $pasanganId = DB::table('pasangan_kelas_ujians')->insertGetId(['kegiatan_ujian_id' => $kegiatanId, 'kelas_a_nama' => '1A', 'kelas_b_nama' => '1B', 'created_at' => $now, 'updated_at' => $now]);
        $ruangId = DB::table('ruang_ujians')->insertGetId(['kode' => 'R1', 'nama' => 'Ruang 1', 'created_at' => $now, 'updated_at' => $now]);
        DB::table('penempatan_ruang_ujians')->insert([
            'siswa_id' => $siswa->id,
            'kegiatan_ujian_id' => $kegiatanId,
            'pasangan_kelas_ujian_id' => $pasanganId,
            'ruang_ujian_id' => $ruangId,
            'nomor_urut' => 1,
            'asal_kelas' => '1A',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        Livewire::test(SiswaManagement::class)
            ->call('confirmDeleteAll')
            ->set('deleteAllConfirmation', 'HAPUS SEMUA')
            ->call('deleteAll')
            ->assertHasNoErrors();

        $this->assertDatabaseCount('siswas', 0);
        $this->assertDatabaseCount('penempatan_ruang_ujians', 0);
        $this->assertDatabaseCount('kegiatan_ujians', 1);
    }
}
