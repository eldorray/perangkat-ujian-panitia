<?php

namespace App\Livewire\Admin;

use App\Models\JadwalUjian;
use App\Models\KegiatanUjian;
use App\Models\Kelas;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Jadwal Ujian')]
class JadwalUjianManagement extends Component
{
    public KegiatanUjian $kegiatanUjian;

    // Form properties
    public string $tanggal = '';
    public string $jam_mulai = '';
    public string $jam_selesai = '';
    public string $mata_pelajaran = '';
    public string $kelompok_kelas = '';
    public string $keterangan = '';

    // Filter
    public string $filterKelompok = '';

    // Modal states
    public bool $showFormModal = false;
    public ?int $editingId = null;

    public function mount(int $id): void
    {
        $this->kegiatanUjian = KegiatanUjian::with('tahunAjaran')->findOrFail($id);
    }

    public function render(): View
    {
        // Get unique kelompok kelas options from Kelas tingkat
        $kelompokOptions = Kelas::select('tingkat')
            ->distinct()
            ->orderBy('tingkat')
            ->pluck('tingkat')
            ->toArray();

        $query = JadwalUjian::where('kegiatan_ujian_id', $this->kegiatanUjian->id);

        if ($this->filterKelompok !== '') {
            $query->where('kelompok_kelas', $this->filterKelompok);
        }

        $jadwalGrouped = $query
            ->orderBy('kelompok_kelas')
            ->orderBy('tanggal')
            ->orderBy('sort_order')
            ->orderBy('jam_mulai')
            ->get()
            ->groupBy(fn($item) => $item->kelompok_kelas ?: 'Semua Kelas')
            ->map(fn($group) => $group->groupBy(fn($item) => $item->tanggal->format('Y-m-d')));

        return view('livewire.admin.jadwal-ujian-management', compact('jadwalGrouped', 'kelompokOptions'));
    }

    public function create(): void
    {
        $this->reset(['tanggal', 'jam_mulai', 'jam_selesai', 'mata_pelajaran', 'kelompok_kelas', 'keterangan', 'editingId']);
        $this->showFormModal = true;
    }

    public function edit(int $id): void
    {
        $jadwal = JadwalUjian::findOrFail($id);

        $this->editingId = $id;
        $this->tanggal = $jadwal->tanggal->format('Y-m-d');
        $this->jam_mulai = $jadwal->jam_mulai;
        $this->jam_selesai = $jadwal->jam_selesai;
        $this->mata_pelajaran = $jadwal->mata_pelajaran;
        $this->kelompok_kelas = $jadwal->kelompok_kelas ?? '';
        $this->keterangan = $jadwal->keterangan ?? '';

        $this->showFormModal = true;
    }

    public function save(): void
    {
        $this->validate([
            'tanggal' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'mata_pelajaran' => 'required|string|max:255',
        ], [
            'tanggal.required' => 'Tanggal wajib diisi',
            'jam_mulai.required' => 'Jam mulai wajib diisi',
            'jam_selesai.required' => 'Jam selesai wajib diisi',
            'mata_pelajaran.required' => 'Mata pelajaran wajib diisi',
        ]);

        $data = [
            'kegiatan_ujian_id' => $this->kegiatanUjian->id,
            'tanggal' => $this->tanggal,
            'jam_mulai' => $this->jam_mulai,
            'jam_selesai' => $this->jam_selesai,
            'mata_pelajaran' => $this->mata_pelajaran,
            'kelompok_kelas' => $this->kelompok_kelas ?: null,
            'keterangan' => $this->keterangan ?: null,
        ];

        if ($this->editingId) {
            $jadwal = JadwalUjian::findOrFail($this->editingId);
            $jadwal->update($data);
        } else {
            // Set sort order
            $maxSort = JadwalUjian::where('kegiatan_ujian_id', $this->kegiatanUjian->id)
                ->where('tanggal', $this->tanggal)
                ->where('kelompok_kelas', $this->kelompok_kelas ?: null)
                ->max('sort_order') ?? 0;
            $data['sort_order'] = $maxSort + 1;

            JadwalUjian::create($data);
        }

        $this->closeModal();
        session()->flash('message', 'Jadwal ujian berhasil ' . ($this->editingId ? 'diperbarui' : 'ditambahkan'));
    }

    public function closeModal(): void
    {
        $this->showFormModal = false;
        $this->editingId = null;
    }

    public function delete(int $id): void
    {
        JadwalUjian::findOrFail($id)->delete();
        session()->flash('message', 'Jadwal ujian berhasil dihapus');
    }

    /**
     * Duplicate a jadwal entry for another kelompok kelas
     */
    public function duplicate(int $id): void
    {
        $jadwal = JadwalUjian::findOrFail($id);

        $this->editingId = null;
        $this->tanggal = $jadwal->tanggal->format('Y-m-d');
        $this->jam_mulai = $jadwal->jam_mulai;
        $this->jam_selesai = $jadwal->jam_selesai;
        $this->mata_pelajaran = $jadwal->mata_pelajaran;
        $this->kelompok_kelas = '';
        $this->keterangan = $jadwal->keterangan ?? '';

        $this->showFormModal = true;
    }
}
