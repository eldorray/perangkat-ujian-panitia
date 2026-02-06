<?php

namespace App\Livewire\Admin;

use App\Models\KegiatanUjian;
use App\Models\SchoolSetting;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Tata Tertib Ujian')]
class TataTertibUjian extends Component
{
    public KegiatanUjian $kegiatanUjian;

    // Custom tata tertib content
    public string $tataTertibPeserta = '';
    public string $tataTertibPengawas = '';

    // View mode: 'peserta' or 'pengawas'
    public string $viewMode = 'peserta';

    // Show preview
    public bool $showPreview = false;

    protected array $defaultTataTertibPeserta = [
        'Peserta wajib hadir di ruang ujian 15 menit sebelum ujian dimulai.',
        'Peserta wajib membawa kartu peserta ujian dan menunjukkannya kepada pengawas.',
        'Peserta wajib duduk sesuai dengan nomor tempat duduk yang telah ditentukan.',
        'Peserta dilarang membawa alat komunikasi (HP), catatan, atau buku ke dalam ruang ujian.',
        'Peserta dilarang berbicara, menoleh, atau melakukan gerakan yang mencurigakan selama ujian berlangsung.',
        'Peserta yang terlambat lebih dari 30 menit tidak diperkenankan mengikuti ujian.',
        'Peserta yang telah selesai mengerjakan harus tetap duduk sampai waktu ujian berakhir atau diperbolehkan keluar.',
        'Peserta yang melanggar tata tertib akan diberi peringatan atau dibatalkan hasil ujiannya.',
        'Peserta wajib menjaga kebersihan dan ketertiban ruang ujian.',
        'Hal-hal yang belum diatur dalam tata tertib ini akan ditentukan kemudian oleh panitia.',
    ];

    protected array $defaultTataTertibPengawas = [
        'Pengawas wajib hadir di ruang ujian 30 menit sebelum ujian dimulai.',
        'Pengawas wajib memeriksa kartu peserta dan mengabsen kehadiran peserta.',
        'Pengawas wajib membacakan tata tertib ujian sebelum ujian dimulai.',
        'Pengawas wajib membagikan soal dan lembar jawaban sesuai jumlah peserta.',
        'Pengawas dilarang membawa alat komunikasi (HP) dalam keadaan aktif di ruang ujian.',
        'Pengawas wajib mengawasi jalannya ujian dengan berdiri/berkeliling dan tidak duduk terus-menerus.',
        'Pengawas wajib mencatat setiap pelanggaran yang terjadi dalam berita acara.',
        'Pengawas wajib mengumpulkan lembar jawaban peserta sesuai nomor urut.',
        'Pengawas wajib mengisi dan menandatangani berita acara pelaksanaan ujian.',
        'Pengawas wajib menyerahkan seluruh berkas ujian kepada panitia setelah ujian selesai.',
    ];

    public function mount(int $id): void
    {
        $this->kegiatanUjian = KegiatanUjian::with('tahunAjaran')->findOrFail($id);

        // Load saved content or use default
        $savedPeserta = session("tata_tertib_peserta_{$id}");
        $savedPengawas = session("tata_tertib_pengawas_{$id}");

        $this->tataTertibPeserta = $savedPeserta ?: implode("\n", $this->defaultTataTertibPeserta);
        $this->tataTertibPengawas = $savedPengawas ?: implode("\n", $this->defaultTataTertibPengawas);
    }

    public function saveContent(): void
    {
        session(["tata_tertib_peserta_{$this->kegiatanUjian->id}" => $this->tataTertibPeserta]);
        session(["tata_tertib_pengawas_{$this->kegiatanUjian->id}" => $this->tataTertibPengawas]);

        $this->dispatch('toast', type: 'success', message: 'Tata tertib berhasil disimpan!');
    }

    public function resetToDefault(): void
    {
        if ($this->viewMode === 'peserta') {
            $this->tataTertibPeserta = implode("\n", $this->defaultTataTertibPeserta);
        } else {
            $this->tataTertibPengawas = implode("\n", $this->defaultTataTertibPengawas);
        }

        $this->saveContent();
        $this->dispatch('toast', type: 'success', message: 'Tata tertib dikembalikan ke default!');
    }

    public function setViewMode(string $mode): void
    {
        $this->viewMode = $mode;
        $this->showPreview = false;
    }

    public function togglePreview(): void
    {
        $this->showPreview = !$this->showPreview;
    }

    public function render(): View
    {
        $schoolSettings = SchoolSetting::getAllSettings();

        $tataTertibList = $this->viewMode === 'peserta'
            ? array_filter(explode("\n", $this->tataTertibPeserta))
            : array_filter(explode("\n", $this->tataTertibPengawas));

        return view('livewire.admin.tata-tertib-ujian', compact(
            'schoolSettings',
            'tataTertibList'
        ));
    }
}
