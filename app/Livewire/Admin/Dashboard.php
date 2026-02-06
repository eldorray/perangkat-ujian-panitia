<?php

namespace App\Livewire\Admin;

use App\Models\Guru;
use App\Models\KegiatanUjian;
use App\Models\Kelas;
use App\Models\Pelajaran;
use App\Models\RuangUjian;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Dashboard')]
class Dashboard extends Component
{
    public function render(): View
    {
        $stats = [
            'siswa' => Siswa::count(),
            'siswa_aktif' => Siswa::where('status', 'Aktif')->count(),
            'guru' => Guru::count(),
            'guru_aktif' => Guru::where('is_active', true)->count(),
            'pelajaran' => Pelajaran::count(),
            'pelajaran_aktif' => Pelajaran::where('is_active', true)->count(),
            'kelas' => Kelas::count(),
            'ruang_ujian' => RuangUjian::count(),
            'kegiatan_ujian' => KegiatanUjian::count(),
            'users' => User::count(),
        ];

        $tahunAjaranAktif = TahunAjaran::where('is_active', true)->first();
        
        // Recent kegiatan ujian
        $recentKegiatanUjian = KegiatanUjian::latest()->take(5)->get();

        return view('livewire.admin.dashboard', compact('stats', 'tahunAjaranAktif', 'recentKegiatanUjian'));
    }
}
