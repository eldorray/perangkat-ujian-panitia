<?php

namespace App\Livewire\Admin;

use App\Models\KegiatanUjian;
use App\Models\LpjPanitia;
use App\Models\RencanaAnggaran;
use App\Models\SchoolSetting;
use App\Models\SuratKeputusan;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('LPJ Panitia')]
class LpjPanitiaManagement extends Component
{
    public KegiatanUjian $kegiatanUjian;

    // Form properties
    public string $nomor_surat = '';
    public string $tanggal_surat = '';
    public string $pendahuluan = '';
    public array $dasar_pelaksanaan = [''];
    public array $tujuan = [''];
    public string $waktu_tempat = '';
    public string $susunan_panitia_text = '';
    public array $pelaksanaan_kegiatan = [''];
    public array $hasil_pelaksanaan = [''];
    public array $kendala_hambatan = [''];
    public string $kesimpulan = '';
    public array $saran = [''];
    public string $penutup = '';
    public string $tempat_ttd = '';
    public string $nama_ketua = '';
    public string $nip_ketua = '';
    public string $nama_kepala_sekolah = '';
    public string $nip_kepala_sekolah = '';

    // Modal states
    public bool $showFormModal = false;
    public ?int $editingId = null;

    public function mount(int $id): void
    {
        $this->kegiatanUjian = KegiatanUjian::with('tahunAjaran')->findOrFail($id);
        $this->tanggal_surat = now()->format('Y-m-d');
    }

    public function render(): View
    {
        $lpjList = LpjPanitia::where('kegiatan_ujian_id', $this->kegiatanUjian->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('livewire.admin.lpj-panitia-management', compact('lpjList'));
    }

    public function create(): void
    {
        $this->resetForm();
        $this->tanggal_surat = now()->format('Y-m-d');

        $schoolSettings = SchoolSetting::getAllSettings();
        $namaUjian = $this->kegiatanUjian->nama_ujian;
        $namaSekolah = $schoolSettings['nama_sekolah'] ?? '';
        $tahunAjaran = $this->kegiatanUjian->tahunAjaran->nama ?? '';

        $this->tempat_ttd = $schoolSettings['kabupaten'] ?? '';
        $this->nama_kepala_sekolah = $schoolSettings['kepala_sekolah'] ?? '';
        $this->nip_kepala_sekolah = $schoolSettings['nip_kepala_sekolah'] ?? '';

        // Try to get ketua from existing SK
        $sk = SuratKeputusan::where('kegiatan_ujian_id', $this->kegiatanUjian->id)->first();
        if ($sk) {
            $ketua = $sk->panitia()->wherePivot('jabatan', 'Ketua')->first();
            if ($ketua) {
                $this->nama_ketua = $ketua->full_name_with_titles;
                $this->nip_ketua = $ketua->nip ?? '';
            }
        }

        // Try to get ketua & bendahara from rencana anggaran
        $ra = RencanaAnggaran::where('kegiatan_ujian_id', $this->kegiatanUjian->id)->first();
        if ($ra) {
            if (empty($this->nama_ketua) && $ra->nama_ketua) {
                $this->nama_ketua = $ra->nama_ketua;
                $this->nip_ketua = $ra->nip_ketua ?? '';
            }
        }

        // Default content
        $this->pendahuluan = "<p>Puji syukur kami panjatkan kehadirat Allah SWT yang telah memberikan rahmat dan hidayah-Nya sehingga pelaksanaan {$namaUjian} {$namaSekolah} Tahun Pelajaran {$tahunAjaran} dapat terlaksana dengan baik dan lancar.</p><p>Laporan Pertanggungjawaban (LPJ) ini disusun sebagai bentuk pertanggungjawaban panitia pelaksana {$namaUjian} kepada Kepala {$namaSekolah} atas pelaksanaan kegiatan yang telah diamanahkan.</p>";

        $this->dasar_pelaksanaan = [
            'Undang-undang Republik Indonesia Nomor 20 Tahun 2003 tentang Sistem Pendidikan Nasional',
            'Surat Keputusan Kepala ' . $namaSekolah . ' tentang Penetapan Panitia ' . $namaUjian,
        ];

        $this->tujuan = [
            'Melaporkan pelaksanaan ' . $namaUjian . ' secara keseluruhan',
            'Melaporkan pertanggungjawaban penggunaan anggaran kegiatan',
            'Memberikan evaluasi terhadap pelaksanaan kegiatan',
        ];

        $this->waktu_tempat = '<p>' . $namaUjian . ' dilaksanakan di ' . $namaSekolah . ' pada Tahun Pelajaran ' . $tahunAjaran . '.</p>';

        $this->pelaksanaan_kegiatan = [
            'Persiapan: Pembentukan panitia, rapat koordinasi, penyusunan jadwal, dan pengadaan soal ujian.',
            'Pelaksanaan: Ujian dilaksanakan sesuai dengan jadwal yang telah ditetapkan.',
            'Pengawasan: Pengawasan dilakukan oleh guru-guru yang telah ditunjuk sesuai jadwal pengawasan.',
        ];

        $this->hasil_pelaksanaan = [
            'Kegiatan ' . $namaUjian . ' berjalan dengan baik dan lancar sesuai jadwal yang telah ditetapkan.',
            'Seluruh peserta ujian mengikuti kegiatan dengan tertib.',
        ];

        $this->kendala_hambatan = ['-'];

        $this->kesimpulan = '<p>Secara keseluruhan, pelaksanaan ' . $namaUjian . ' ' . $namaSekolah . ' Tahun Pelajaran ' . $tahunAjaran . ' berjalan dengan baik dan lancar. Panitia telah berusaha semaksimal mungkin dalam melaksanakan tugas yang diamanahkan.</p>';

        $this->saran = [
            'Persiapan pelaksanaan ujian hendaknya dilakukan lebih awal agar lebih matang.',
            'Koordinasi antar panitia perlu ditingkatkan untuk kelancaran pelaksanaan kegiatan.',
        ];

        $this->penutup = "<p>Demikian Laporan Pertanggungjawaban ini kami sampaikan. Atas perhatian dan kerjasamanya kami ucapkan terima kasih.</p><p>Semoga laporan ini dapat menjadi bahan evaluasi untuk pelaksanaan kegiatan serupa di masa yang akan datang.</p>";

        $this->showFormModal = true;
    }

    public function edit(int $id): void
    {
        $lpj = LpjPanitia::findOrFail($id);

        $this->editingId = $id;
        $this->nomor_surat = $lpj->nomor_surat ?? '';
        $this->tanggal_surat = $lpj->tanggal_surat ? $lpj->tanggal_surat->format('Y-m-d') : now()->format('Y-m-d');
        $this->pendahuluan = $lpj->pendahuluan ?? '';
        $this->dasar_pelaksanaan = $lpj->dasar_pelaksanaan ?? [''];
        $this->tujuan = $lpj->tujuan ?? [''];
        $this->waktu_tempat = $lpj->waktu_tempat ?? '';
        $this->susunan_panitia_text = $lpj->susunan_panitia_text ?? '';
        $this->pelaksanaan_kegiatan = $lpj->pelaksanaan_kegiatan ?? [''];
        $this->hasil_pelaksanaan = $lpj->hasil_pelaksanaan ?? [''];
        $this->kendala_hambatan = $lpj->kendala_hambatan ?? [''];
        $this->kesimpulan = $lpj->kesimpulan ?? '';
        $this->saran = $lpj->saran ?? [''];
        $this->penutup = $lpj->penutup ?? '';
        $this->tempat_ttd = $lpj->tempat_ttd ?? '';
        $this->nama_ketua = $lpj->nama_ketua ?? '';
        $this->nip_ketua = $lpj->nip_ketua ?? '';
        $this->nama_kepala_sekolah = $lpj->nama_kepala_sekolah ?? '';
        $this->nip_kepala_sekolah = $lpj->nip_kepala_sekolah ?? '';

        $this->showFormModal = true;
    }

    // Dynamic array row management
    public function addRow(string $field): void
    {
        $this->{$field}[] = '';
    }

    public function removeRow(string $field, int $index): void
    {
        unset($this->{$field}[$index]);
        $this->{$field} = array_values($this->{$field});
    }

    public function save(): void
    {
        $this->validate([
            'tanggal_surat' => 'required|date',
            'pendahuluan' => 'required|string',
            'kesimpulan' => 'required|string',
            'penutup' => 'required|string',
        ], [
            'tanggal_surat.required' => 'Tanggal wajib diisi',
            'pendahuluan.required' => 'Pendahuluan wajib diisi',
            'kesimpulan.required' => 'Kesimpulan wajib diisi',
            'penutup.required' => 'Penutup wajib diisi',
        ]);

        $filterArray = fn(array $items) => array_values(array_filter($items, fn($item) => trim($item) !== ''));
        $cleanHtml = fn(?string $html) => $html ? strip_tags($html, '<p><br><strong><em><u><ol><ul><li><span>') : null;

        $data = [
            'kegiatan_ujian_id' => $this->kegiatanUjian->id,
            'nomor_surat' => $this->nomor_surat ?: null,
            'tanggal_surat' => $this->tanggal_surat,
            'pendahuluan' => $cleanHtml($this->pendahuluan),
            'dasar_pelaksanaan' => $filterArray($this->dasar_pelaksanaan),
            'tujuan' => $filterArray($this->tujuan),
            'waktu_tempat' => $cleanHtml($this->waktu_tempat),
            'susunan_panitia_text' => $cleanHtml($this->susunan_panitia_text),
            'pelaksanaan_kegiatan' => $filterArray($this->pelaksanaan_kegiatan),
            'hasil_pelaksanaan' => $filterArray($this->hasil_pelaksanaan),
            'kendala_hambatan' => $filterArray($this->kendala_hambatan),
            'kesimpulan' => $cleanHtml($this->kesimpulan),
            'saran' => $filterArray($this->saran),
            'penutup' => $cleanHtml($this->penutup),
            'tempat_ttd' => $this->tempat_ttd ?: null,
            'nama_ketua' => $this->nama_ketua ?: null,
            'nip_ketua' => $this->nip_ketua ?: null,
            'nama_kepala_sekolah' => $this->nama_kepala_sekolah ?: null,
            'nip_kepala_sekolah' => $this->nip_kepala_sekolah ?: null,
        ];

        if ($this->editingId) {
            $lpj = LpjPanitia::findOrFail($this->editingId);
            $lpj->update($data);
        } else {
            LpjPanitia::create($data);
        }

        $this->closeModal();
        session()->flash('message', 'LPJ berhasil ' . ($this->editingId ? 'diperbarui' : 'ditambahkan'));
    }

    public function closeModal(): void
    {
        $this->showFormModal = false;
        $this->editingId = null;
    }

    public function delete(int $id): void
    {
        LpjPanitia::findOrFail($id)->delete();
        session()->flash('message', 'LPJ berhasil dihapus');
    }

    private function resetForm(): void
    {
        $this->reset([
            'nomor_surat', 'pendahuluan', 'waktu_tempat', 'susunan_panitia_text',
            'kesimpulan', 'penutup', 'tempat_ttd',
            'nama_ketua', 'nip_ketua', 'nama_kepala_sekolah', 'nip_kepala_sekolah',
            'editingId',
        ]);
        $this->dasar_pelaksanaan = [''];
        $this->tujuan = [''];
        $this->pelaksanaan_kegiatan = [''];
        $this->hasil_pelaksanaan = [''];
        $this->kendala_hambatan = [''];
        $this->saran = [''];
    }
}
