<?php

namespace App\Livewire\Admin;

use App\Models\KegiatanUjian;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Perangkat Ujian')]
class PerangkatUjian extends Component
{
    public KegiatanUjian $kegiatanUjian;
    public bool $showUnlockOverlay = false;
    public string $unlockPin = '';
    public bool $isUnlocked = false;

    protected array $messages = [
        'unlockPin.required' => 'PIN wajib diisi.',
        'unlockPin.digits' => 'PIN harus 6 digit angka.',
    ];

    public function mount(int $id): void
    {
        $this->kegiatanUjian = KegiatanUjian::with('tahunAjaran')->findOrFail($id);
        
        // Check if kegiatan is locked
        if ($this->kegiatanUjian->is_locked) {
            $this->showUnlockOverlay = true;
        } else {
            $this->isUnlocked = true;
        }
    }

    public function unlockAndAccess(): void
    {
        $this->validate([
            'unlockPin' => 'required|digits:6',
        ]);

        if ($this->kegiatanUjian->verifyPin($this->unlockPin)) {
            $this->showUnlockOverlay = false;
            $this->isUnlocked = true;
            $this->unlockPin = '';
        } else {
            $this->addError('unlockPin', 'PIN yang dimasukkan salah.');
        }
    }

    public function render(): View
    {
        // Urutan berdasarkan alur kerja pembuatan perangkat ujian
        $perangkatList = [
            [
                'id' => 'rencana-anggaran',
                'nomor' => 1,
                'nama' => 'Rencana Anggaran',
                'deskripsi' => 'Rencana anggaran kegiatan ujian',
                'icon' => 'banknotes',
                'status' => 'ready',
                'route' => route('admin.kegiatan-ujian.rencana-anggaran', $this->kegiatanUjian->id),
            ],
            [
                'id' => 'surat-tugas',
                'nomor' => 2,
                'nama' => 'Surat Tugas',
                'deskripsi' => 'Surat tugas mengawas dan mengoreksi ujian',
                'icon' => 'document-check',
                'status' => 'ready',
                'route' => route('admin.kegiatan-ujian.surat-tugas', $this->kegiatanUjian->id),
            ],
            [
                'id' => 'jadwal',
                'nomor' => 3,
                'nama' => 'Jadwal Ujian',
                'deskripsi' => 'Jadwal pelaksanaan ujian per ruang dan sesi',
                'icon' => 'calendar',
                'status' => 'ready',
                'route' => route('admin.kegiatan-ujian.jadwal', $this->kegiatanUjian->id),
            ],
            [
                'id' => 'penempatan-per-kelas',
                'nomor' => 4,
                'nama' => 'Penempatan Per Kelas',
                'deskripsi' => 'Penempatan siswa ke ruang ujian berdasarkan kelas (tanpa acak)',
                'icon' => 'building-office',
                'status' => 'ready',
                'route' => route('admin.kegiatan-ujian.penempatan-per-kelas', $this->kegiatanUjian->id),
            ],
            [
                'id' => 'acak-kelas',
                'nomor' => 5,
                'nama' => 'Acak Kelas',
                'deskripsi' => 'Pasangkan kelas dan acak penempatan siswa ke ruang',
                'icon' => 'arrows-right-left',
                'status' => 'ready',
                'route' => route('admin.kegiatan-ujian.acak-kelas', $this->kegiatanUjian->id),
            ],
            [
                'id' => 'kartu-peserta',
                'nomor' => 6,
                'nama' => 'Kartu Peserta',
                'deskripsi' => 'Cetak kartu peserta ujian',
                'icon' => 'identification',
                'status' => 'ready',
                'route' => route('admin.kegiatan-ujian.kartu-peserta', $this->kegiatanUjian->id),
            ],
            [
                'id' => 'denah-ruang',
                'nomor' => 7,
                'nama' => 'Denah Ruang',
                'deskripsi' => 'Pembagian ruang dan posisi duduk peserta',
                'icon' => 'map',
                'status' => 'ready',
                'route' => route('admin.kegiatan-ujian.denah-ruang', $this->kegiatanUjian->id),
            ],
            [
                'id' => 'daftar-hadir',
                'nomor' => 8,
                'nama' => 'Daftar Hadir Peserta',
                'deskripsi' => 'Cetak daftar hadir peserta ujian per ruang',
                'icon' => 'clipboard-document-list',
                'status' => 'ready',
                'route' => route('admin.kegiatan-ujian.daftar-hadir', $this->kegiatanUjian->id),
            ],
            [
                'id' => 'menu-panitia',
                'nomor' => 9,
                'nama' => 'Menu Panitia',
                'deskripsi' => 'Modul-modul untuk kebutuhan panitia ujian',
                'icon' => 'user-group',
                'status' => 'ready',
                'route' => route('admin.kegiatan-ujian.daftar-hadir-panitia', $this->kegiatanUjian->id),
            ],
            [
                'id' => 'pos',
                'nomor' => 10,
                'nama' => 'Prosedur Operasional Standar (POS)',
                'deskripsi' => 'Dokumen panduan pelaksanaan ujian',
                'icon' => 'document-text',
                'status' => 'ready',
                'route' => route('admin.kegiatan-ujian.pos', $this->kegiatanUjian->id),
            ],
        ];

        return view('livewire.admin.perangkat-ujian', compact('perangkatList'));
    }
}
