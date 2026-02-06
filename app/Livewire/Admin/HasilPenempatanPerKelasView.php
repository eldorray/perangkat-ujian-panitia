<?php

namespace App\Livewire\Admin;

use App\Models\KegiatanUjian;
use App\Models\PenempatanRuangUjian;
use App\Models\RuangUjian;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Hasil Penempatan Per Kelas')]
class HasilPenempatanPerKelasView extends Component
{
    public KegiatanUjian $kegiatanUjian;
    public string $kelasNama;

    // Edit modal state
    public bool $showEditModal = false;
    public ?int $editingPenempatanId = null;
    public ?int $selectedRuangId = null;
    public ?int $selectedNomorUrut = null;

    // Delete confirmation
    public bool $showDeleteModal = false;
    public ?int $deletingId = null;

    public function mount(int $kegiatanUjianId, string $kelasNama): void
    {
        $this->kegiatanUjian = KegiatanUjian::with('tahunAjaran')->findOrFail($kegiatanUjianId);
        $this->kelasNama = $kelasNama;
    }

    public function openEditModal(int $penempatanId): void
    {
        $penempatan = PenempatanRuangUjian::findOrFail($penempatanId);
        $this->editingPenempatanId = $penempatanId;
        $this->selectedRuangId = $penempatan->ruang_ujian_id;
        $this->selectedNomorUrut = $penempatan->nomor_urut;
        $this->showEditModal = true;
    }

    public function closeEditModal(): void
    {
        $this->showEditModal = false;
        $this->editingPenempatanId = null;
        $this->selectedRuangId = null;
        $this->selectedNomorUrut = null;
    }

    public function updatePenempatan(): void
    {
        $this->validate([
            'selectedRuangId' => 'required|exists:ruang_ujians,id',
            'selectedNomorUrut' => 'required|integer|min:1',
        ], [
            'selectedRuangId.required' => 'Ruang ujian harus dipilih',
            'selectedNomorUrut.required' => 'Nomor urut harus diisi',
            'selectedNomorUrut.min' => 'Nomor urut minimal 1',
        ]);

        $penempatan = PenempatanRuangUjian::findOrFail($this->editingPenempatanId);
        
        // Check capacity if moving to different room
        if ($penempatan->ruang_ujian_id !== $this->selectedRuangId) {
            $ruang = RuangUjian::findOrFail($this->selectedRuangId);
            $currentCount = PenempatanRuangUjian::where('kegiatan_ujian_id', $this->kegiatanUjian->id)
                ->where('kelas_nama', $this->kelasNama)
                ->whereNull('pasangan_kelas_ujian_id')
                ->where('ruang_ujian_id', $this->selectedRuangId)
                ->count();
            
            if ($currentCount >= $ruang->kapasitas) {
                $this->addError('selectedRuangId', "Ruang {$ruang->nama} sudah penuh ({$currentCount}/{$ruang->kapasitas})");
                return;
            }
        }

        $penempatan->update([
            'ruang_ujian_id' => $this->selectedRuangId,
            'nomor_urut' => $this->selectedNomorUrut,
        ]);

        $this->closeEditModal();
        session()->flash('message', 'Penempatan berhasil diperbarui');
    }

    public function confirmDelete(int $id): void
    {
        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function deletePenempatan(): void
    {
        if ($this->deletingId) {
            PenempatanRuangUjian::findOrFail($this->deletingId)->delete();
            session()->flash('message', 'Penempatan berhasil dihapus');
        }
        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    public function render(): View
    {
        $penempatanByRuang = PenempatanRuangUjian::where('kegiatan_ujian_id', $this->kegiatanUjian->id)
            ->where('kelas_nama', $this->kelasNama)
            ->whereNull('pasangan_kelas_ujian_id')
            ->with(['siswa', 'ruangUjian'])
            ->orderBy('ruang_ujian_id')
            ->orderBy('nomor_urut')
            ->get()
            ->groupBy('ruang_ujian_id');

        $totalSiswa = PenempatanRuangUjian::where('kegiatan_ujian_id', $this->kegiatanUjian->id)
            ->where('kelas_nama', $this->kelasNama)
            ->whereNull('pasangan_kelas_ujian_id')
            ->count();
        
        $ruangList = RuangUjian::orderBy('kode')->get();

        return view('livewire.admin.hasil-penempatan-per-kelas-view', compact('penempatanByRuang', 'totalSiswa', 'ruangList'));
    }
}
