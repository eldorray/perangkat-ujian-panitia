<?php

namespace App\Livewire\Admin;

use App\Models\KegiatanUjian;
use App\Models\PasanganKelasUjian;
use App\Models\PenempatanRuangUjian;
use App\Models\RuangUjian;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Hasil Penempatan')]
class HasilPenempatanView extends Component
{
    public KegiatanUjian $kegiatanUjian;
    public PasanganKelasUjian $pasangan;

    // Edit modal state
    public bool $showEditModal = false;
    public ?int $editingPenempatanId = null;
    public ?int $selectedRuangId = null;
    public ?int $selectedNomorUrut = null;

    // Delete confirmation
    public bool $showDeleteModal = false;
    public ?int $deletingId = null;

    public function mount(int $kegiatanUjianId, int $pasanganId): void
    {
        $this->kegiatanUjian = KegiatanUjian::with('tahunAjaran')->findOrFail($kegiatanUjianId);
        $this->pasangan = PasanganKelasUjian::findOrFail($pasanganId);
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
            $currentCount = PenempatanRuangUjian::where('pasangan_kelas_ujian_id', $this->pasangan->id)
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
        $penempatanByRuang = PenempatanRuangUjian::where('pasangan_kelas_ujian_id', $this->pasangan->id)
            ->with(['siswa', 'ruangUjian'])
            ->orderBy('ruang_ujian_id')
            ->orderBy('nomor_urut')
            ->get()
            ->groupBy('ruang_ujian_id');

        $totalSiswa = PenempatanRuangUjian::where('pasangan_kelas_ujian_id', $this->pasangan->id)->count();
        
        $ruangList = RuangUjian::orderBy('kode')->get();

        return view('livewire.admin.hasil-penempatan-view', compact('penempatanByRuang', 'totalSiswa', 'ruangList'));
    }
}
