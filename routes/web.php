<?php

use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\ResetPassword;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\UserManagement;
use App\Livewire\Admin\TahunAjaranManagement;
use App\Livewire\Admin\GuruManagement;
use App\Livewire\Admin\PelajaranManagement;
use App\Livewire\Admin\KelasManagement;
use App\Livewire\Admin\SiswaManagement;
use App\Livewire\Admin\RuangUjianManagement;
use App\Livewire\Admin\SchoolProfile;
use App\Livewire\Admin\KegiatanUjianManagement;
use App\Livewire\Admin\PerangkatUjian;
use App\Livewire\Admin\PosUjianManagement;
use App\Livewire\Admin\KartuPesertaManagement;
use App\Livewire\Admin\AcakKelasManagement;
use App\Livewire\Admin\HasilPenempatanView;
use App\Livewire\Admin\SuratTugasManagement;
use App\Livewire\Admin\JadwalUjianManagement;
use App\Livewire\Admin\PenempatanPerKelasManagement;
use App\Livewire\Admin\HasilPenempatanPerKelasView;
use App\Livewire\Admin\DenahRuangManagement;
use App\Livewire\Admin\DaftarHadirManagement;
use App\Livewire\Admin\DaftarHadirPanitiaManagement;
use App\Livewire\Admin\JadwalMengawasManagement;
use App\Livewire\Admin\RencanaAnggaranManagement;
use App\Livewire\Admin\AppSettings;
use App\Livewire\Admin\BeritaAcaraUjian;
use App\Livewire\Admin\LabelAmplopSoal;
use App\Livewire\Admin\TataTertibUjian;
use App\Livewire\Admin\HonorInsentifKalkulator;
use App\Livewire\Admin\SuratKeputusanManagement;
use App\Livewire\Admin\LpjPanitiaManagement;
use App\Http\Controllers\KartuPesertaController;
use App\Http\Controllers\SuratTugasController;
use App\Http\Controllers\SuratKeputusanController;
use App\Http\Controllers\LpjPanitiaController;
use App\Http\Controllers\JadwalUjianController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::get('dashboard', Dashboard::class)
    ->middleware(['auth'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('login', Login::class)->name('login');
    // Register route disabled - users are created by admin only
    // Route::get('register', Register::class)->name('register');
    Route::get('forgot-password', ForgotPassword::class)->name('password.request');
    Route::get('reset-password/{token}', ResetPassword::class)->name('password.reset');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', function () {
        \Illuminate\Support\Facades\Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('logout');
});

// Admin Routes - Accessible by both Admin and Panitia
Route::middleware(['auth', 'role:' . User::ROLE_ADMIN . ',' . User::ROLE_PANITIA])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Settings - Accessible by both roles
        Route::get('school-profile', SchoolProfile::class)->name('school-profile');

        // Master Data (except users) - Accessible by both roles
        Route::get('tahun-ajaran', TahunAjaranManagement::class)->name('tahun-ajaran');
        Route::get('guru', GuruManagement::class)->name('guru');
        Route::get('pelajaran', PelajaranManagement::class)->name('pelajaran');
        Route::get('kelas', KelasManagement::class)->name('kelas');
        Route::get('siswa', SiswaManagement::class)->name('siswa');
        Route::get('ruang-ujian', RuangUjianManagement::class)->name('ruang-ujian');

        // Kegiatan Ujian - Accessible by both roles
        Route::get('kegiatan-ujian', KegiatanUjianManagement::class)->name('kegiatan-ujian');
        Route::get('kegiatan-ujian/{id}/perangkat', PerangkatUjian::class)->name('kegiatan-ujian.perangkat');
        Route::get('kegiatan-ujian/{kegiatanUjianId}/pos', PosUjianManagement::class)->name('kegiatan-ujian.pos');

        // Kartu Peserta
        Route::get('kegiatan-ujian/{kegiatanUjianId}/kartu-peserta', KartuPesertaManagement::class)->name('kegiatan-ujian.kartu-peserta');

        // Acak Kelas
        Route::get('kegiatan-ujian/{id}/acak-kelas', AcakKelasManagement::class)->name('kegiatan-ujian.acak-kelas');
        Route::get('kegiatan-ujian/{kegiatanUjianId}/acak-kelas/{pasanganId}/hasil', HasilPenempatanView::class)->name('kegiatan-ujian.acak-kelas.hasil');
        Route::get('kegiatan-ujian/{kegiatanUjianId}/kartu-peserta/print', [KartuPesertaController::class, 'print'])->name('kegiatan-ujian.kartu-peserta.print');

        // Penempatan Per Kelas
        Route::get('kegiatan-ujian/{id}/penempatan-per-kelas', PenempatanPerKelasManagement::class)->name('kegiatan-ujian.penempatan-per-kelas');
        Route::get('kegiatan-ujian/{kegiatanUjianId}/penempatan-per-kelas/{kelasNama}/hasil', HasilPenempatanPerKelasView::class)->name('kegiatan-ujian.penempatan-per-kelas.hasil');

        // Denah Ruang
        Route::get('kegiatan-ujian/{id}/denah-ruang', DenahRuangManagement::class)->name('kegiatan-ujian.denah-ruang');

        // Daftar Hadir
        Route::get('kegiatan-ujian/{id}/daftar-hadir', DaftarHadirManagement::class)->name('kegiatan-ujian.daftar-hadir');

        // Menu Panitia
        Route::get('kegiatan-ujian/{id}/daftar-hadir-panitia', DaftarHadirPanitiaManagement::class)->name('kegiatan-ujian.daftar-hadir-panitia');
        Route::get('kegiatan-ujian/{id}/jadwal-mengawas', JadwalMengawasManagement::class)->name('kegiatan-ujian.jadwal-mengawas');
        Route::get('kegiatan-ujian/{id}/berita-acara', BeritaAcaraUjian::class)->name('kegiatan-ujian.berita-acara');
        Route::get('kegiatan-ujian/{id}/label-amplop', LabelAmplopSoal::class)->name('kegiatan-ujian.label-amplop');
        Route::get('kegiatan-ujian/{id}/tata-tertib', TataTertibUjian::class)->name('kegiatan-ujian.tata-tertib');
        Route::get('kegiatan-ujian/{id}/honor-insentif', HonorInsentifKalkulator::class)->name('kegiatan-ujian.honor-insentif');

        // Surat Keputusan
        Route::get('kegiatan-ujian/{id}/surat-keputusan', SuratKeputusanManagement::class)->name('kegiatan-ujian.surat-keputusan');
        Route::get('kegiatan-ujian/{id}/surat-keputusan/{suratKeputusanId}/print', [SuratKeputusanController::class, 'print'])->name('kegiatan-ujian.surat-keputusan.print');

        // LPJ Panitia
        Route::get('kegiatan-ujian/{id}/lpj-panitia', LpjPanitiaManagement::class)->name('kegiatan-ujian.lpj-panitia');
        Route::get('kegiatan-ujian/{id}/lpj-panitia/{lpjId}/print', [LpjPanitiaController::class, 'print'])->name('kegiatan-ujian.lpj-panitia.print');

        // Surat Tugas
        Route::get('kegiatan-ujian/{id}/surat-tugas', SuratTugasManagement::class)->name('kegiatan-ujian.surat-tugas');
        Route::get('kegiatan-ujian/{id}/surat-tugas/{suratTugasId}/print', [SuratTugasController::class, 'print'])->name('kegiatan-ujian.surat-tugas.print');

        // Jadwal Ujian
        Route::get('kegiatan-ujian/{id}/jadwal', JadwalUjianManagement::class)->name('kegiatan-ujian.jadwal');
        Route::get('kegiatan-ujian/{id}/jadwal/print', [JadwalUjianController::class, 'print'])->name('kegiatan-ujian.jadwal.print');

        // Rencana Anggaran
        Route::get('kegiatan-ujian/{kegiatanUjianId}/rencana-anggaran', RencanaAnggaranManagement::class)->name('kegiatan-ujian.rencana-anggaran');
    });

// Admin Only Routes - Only accessible by Admin
Route::middleware(['auth', 'role:' . User::ROLE_ADMIN])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // User Management - Admin only
        Route::get('users', UserManagement::class)->name('users');

        // App Settings - Admin only
        Route::get('app-settings', AppSettings::class)->name('app-settings');
    });

