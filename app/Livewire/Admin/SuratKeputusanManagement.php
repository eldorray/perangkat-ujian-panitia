<?php

namespace App\Livewire\Admin;

use App\Models\Guru;
use App\Models\KegiatanUjian;
use App\Models\SchoolSetting;
use App\Models\SuratKeputusan;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Surat Keputusan')]
class SuratKeputusanManagement extends Component
{
    public KegiatanUjian $kegiatanUjian;

    // Form properties
    public string $nomor_surat = '';
    public string $tanggal_surat = '';
    public string $tentang = '';
    public array $menimbang = [''];
    public array $mengingat = [''];
    public string $memperhatikan = '';
    public string $ditetapkan_di = '';

    // Panitia assignments: [['guru_id' => '', 'jabatan' => '', 'urutan' => 0], ...]
    public array $panitiaAssignments = [];

    // Modal states
    public bool $showFormModal = false;
    public ?int $editingId = null;

    public function mount(int $id): void
    {
        $this->kegiatanUjian = KegiatanUjian::with('tahunAjaran')->findOrFail($id);
        $this->tanggal_surat = now()->format('Y-m-d');
        $this->ditetapkan_di = SchoolSetting::get('kabupaten', '');
    }

    public function render(): View
    {
        $suratKeputusanList = SuratKeputusan::where('kegiatan_ujian_id', $this->kegiatanUjian->id)
            ->withCount('panitia')
            ->orderBy('created_at', 'desc')
            ->get();

        $guruList = Guru::active()->orderBy('full_name')->get();

        return view('livewire.admin.surat-keputusan-management', compact('suratKeputusanList', 'guruList'));
    }

    public function create(): void
    {
        $this->resetForm();
        $this->tanggal_surat = now()->format('Y-m-d');
        $this->ditetapkan_di = SchoolSetting::get('kabupaten', '');
        $this->tentang = 'Penetapan Panitia ' . $this->kegiatanUjian->nama_ujian;

        // Default menimbang
        $this->menimbang = [
            'Bahwa dalam rangka kelancaran pelaksanaan ' . $this->kegiatanUjian->nama_ujian . ' Tahun Pelajaran ' . ($this->kegiatanUjian->tahunAjaran->nama ?? '') . ' dipandang perlu membentuk panitia pelaksana.',
        ];

        // Default mengingat
        $this->mengingat = [
            'Undang-undang Republik Indonesia Nomor 20 Tahun 2003 tentang Sistem Pendidikan Nasional',
        ];

        // Default memperhatikan
        $this->memperhatikan = 'Hasil rapat dewan guru tentang pelaksanaan ' . $this->kegiatanUjian->nama_ujian;

        // Default panitia assignments with all jabatan types
        $this->panitiaAssignments = [];
        foreach (SuratKeputusan::jabatanList() as $index => $jabatan) {
            $this->panitiaAssignments[] = [
                'guru_id' => '',
                'jabatan' => $jabatan,
                'urutan' => $index + 1,
            ];
        }

        $this->showFormModal = true;
    }

    public function edit(int $id): void
    {
        $suratKeputusan = SuratKeputusan::with('panitia')->findOrFail($id);

        $this->editingId = $id;
        $this->nomor_surat = $suratKeputusan->nomor_surat;
        $this->tanggal_surat = $suratKeputusan->tanggal_surat->format('Y-m-d');
        $this->tentang = $suratKeputusan->tentang;
        $this->menimbang = $suratKeputusan->menimbang ?? [''];
        $this->mengingat = $suratKeputusan->mengingat ?? [''];
        $this->memperhatikan = $suratKeputusan->memperhatikan ?? '';
        $this->ditetapkan_di = $suratKeputusan->ditetapkan_di ?? '';

        // Load panitia assignments
        $this->panitiaAssignments = [];
        foreach ($suratKeputusan->panitia as $guru) {
            $this->panitiaAssignments[] = [
                'guru_id' => (string) $guru->id,
                'jabatan' => $guru->pivot->jabatan,
                'urutan' => $guru->pivot->urutan,
            ];
        }

        // If no assignments, add defaults
        if (empty($this->panitiaAssignments)) {
            foreach (SuratKeputusan::jabatanList() as $index => $jabatan) {
                $this->panitiaAssignments[] = [
                    'guru_id' => '',
                    'jabatan' => $jabatan,
                    'urutan' => $index + 1,
                ];
            }
        }

        $this->showFormModal = true;
    }

    public function addPanitiaRow(): void
    {
        $this->panitiaAssignments[] = [
            'guru_id' => '',
            'jabatan' => 'Anggota',
            'urutan' => count($this->panitiaAssignments) + 1,
        ];
    }

    public function removePanitiaRow(int $index): void
    {
        unset($this->panitiaAssignments[$index]);
        $this->panitiaAssignments = array_values($this->panitiaAssignments);
    }

    public function addMenimbangRow(): void
    {
        $this->menimbang[] = '';
    }

    public function removeMenimbangRow(int $index): void
    {
        unset($this->menimbang[$index]);
        $this->menimbang = array_values($this->menimbang);
    }

    public function addMengingatRow(): void
    {
        $this->mengingat[] = '';
    }

    public function removeMengingatRow(int $index): void
    {
        unset($this->mengingat[$index]);
        $this->mengingat = array_values($this->mengingat);
    }

    public function save(): void
    {
        $this->validate([
            'nomor_surat' => 'required|string|max:100',
            'tanggal_surat' => 'required|date',
            'tentang' => 'required|string',
            'menimbang' => 'required|array|min:1',
            'mengingat' => 'required|array|min:1',
            'panitiaAssignments' => 'required|array|min:1',
            'panitiaAssignments.*.guru_id' => 'required|exists:gurus,id',
            'panitiaAssignments.*.jabatan' => 'required|string',
        ], [
            'nomor_surat.required' => 'Nomor surat wajib diisi',
            'tentang.required' => 'Perihal/tentang wajib diisi',
            'menimbang.required' => 'Minimal 1 poin menimbang',
            'mengingat.required' => 'Minimal 1 poin mengingat',
            'panitiaAssignments.required' => 'Minimal 1 panitia harus ditambahkan',
            'panitiaAssignments.*.guru_id.required' => 'Pilih guru untuk setiap jabatan',
            'panitiaAssignments.*.guru_id.exists' => 'Guru tidak valid',
            'panitiaAssignments.*.jabatan.required' => 'Jabatan wajib diisi',
        ]);

        // Filter empty menimbang/mengingat
        $menimbang = array_values(array_filter($this->menimbang, fn($item) => trim($item) !== ''));
        $mengingat = array_values(array_filter($this->mengingat, fn($item) => trim($item) !== ''));

        $data = [
            'kegiatan_ujian_id' => $this->kegiatanUjian->id,
            'nomor_surat' => $this->nomor_surat,
            'tanggal_surat' => $this->tanggal_surat,
            'tentang' => $this->tentang,
            'menimbang' => $menimbang,
            'mengingat' => $mengingat,
            'memperhatikan' => $this->memperhatikan ?: null,
            'ditetapkan_di' => $this->ditetapkan_di ?: null,
        ];

        if ($this->editingId) {
            $suratKeputusan = SuratKeputusan::findOrFail($this->editingId);
            $suratKeputusan->update($data);
        } else {
            $suratKeputusan = SuratKeputusan::create($data);
        }

        // Sync panitia assignments
        $suratKeputusan->panitia()->detach();
        foreach ($this->panitiaAssignments as $assignment) {
            if (!empty($assignment['guru_id'])) {
                $suratKeputusan->panitia()->attach($assignment['guru_id'], [
                    'jabatan' => $assignment['jabatan'],
                    'urutan' => $assignment['urutan'] ?? 0,
                ]);
            }
        }

        $this->closeModal();
        session()->flash('message', 'Surat Keputusan berhasil ' . ($this->editingId ? 'diperbarui' : 'ditambahkan'));
    }

    public function closeModal(): void
    {
        $this->showFormModal = false;
        $this->editingId = null;
    }

    public function delete(int $id): void
    {
        SuratKeputusan::findOrFail($id)->delete();
        session()->flash('message', 'Surat Keputusan berhasil dihapus');
    }

    private function resetForm(): void
    {
        $this->reset([
            'nomor_surat', 'tentang', 'memperhatikan', 'ditetapkan_di',
            'panitiaAssignments', 'editingId',
        ]);
        $this->menimbang = [''];
        $this->mengingat = [''];
    }
}
