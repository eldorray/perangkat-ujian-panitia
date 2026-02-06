<?php

namespace App\Livewire\Admin;

use App\Models\Guru;
use App\Models\KegiatanUjian;
use App\Models\SuratTugas;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Surat Tugas')]
class SuratTugasManagement extends Component
{
    public KegiatanUjian $kegiatanUjian;
    
    // Form properties
    public string $nomor_surat = '';
    public string $tanggal_surat = '';
    public string $dasar_surat = '';
    public string $untuk_keperluan = '';
    public string $tanggal_mulai = '';
    public string $tanggal_selesai = '';
    public array $selectedGurus = [];
    
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
        $suratTugasList = SuratTugas::where('kegiatan_ujian_id', $this->kegiatanUjian->id)
            ->withCount('gurus')
            ->orderBy('created_at', 'desc')
            ->get();
        
        $guruList = Guru::active()->orderBy('full_name')->get();

        return view('livewire.admin.surat-tugas-management', compact('suratTugasList', 'guruList'));
    }

    public function create(): void
    {
        $this->reset(['nomor_surat', 'dasar_surat', 'untuk_keperluan', 'tanggal_mulai', 'tanggal_selesai', 'selectedGurus', 'editingId']);
        $this->tanggal_surat = now()->format('Y-m-d');
        $this->showFormModal = true;
    }

    public function edit(int $id): void
    {
        $suratTugas = SuratTugas::with('gurus')->findOrFail($id);
        
        $this->editingId = $id;
        $this->nomor_surat = $suratTugas->nomor_surat;
        $this->tanggal_surat = $suratTugas->tanggal_surat->format('Y-m-d');
        $this->dasar_surat = $suratTugas->dasar_surat ?? '';
        $this->untuk_keperluan = $suratTugas->untuk_keperluan;
        $this->tanggal_mulai = $suratTugas->tanggal_mulai->format('Y-m-d');
        $this->tanggal_selesai = $suratTugas->tanggal_selesai->format('Y-m-d');
        $this->selectedGurus = $suratTugas->gurus->pluck('id')->map(fn($id) => (string) $id)->toArray();
        
        $this->showFormModal = true;
    }

    public function save(): void
    {
        $this->validate([
            'nomor_surat' => 'required|string|max:100',
            'tanggal_surat' => 'required|date',
            'untuk_keperluan' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'selectedGurus' => 'required|array|min:1',
        ], [
            'nomor_surat.required' => 'Nomor surat wajib diisi',
            'untuk_keperluan.required' => 'Keperluan wajib diisi',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai',
            'selectedGurus.required' => 'Minimal pilih 1 guru',
            'selectedGurus.min' => 'Minimal pilih 1 guru',
        ]);

        $data = [
            'kegiatan_ujian_id' => $this->kegiatanUjian->id,
            'jenis' => 'mengawas', // Default value
            'nomor_surat' => $this->nomor_surat,
            'tanggal_surat' => $this->tanggal_surat,
            'dasar_surat' => $this->dasar_surat ?: null,
            'untuk_keperluan' => $this->untuk_keperluan,
            'tanggal_mulai' => $this->tanggal_mulai,
            'tanggal_selesai' => $this->tanggal_selesai,
        ];

        if ($this->editingId) {
            $suratTugas = SuratTugas::findOrFail($this->editingId);
            $suratTugas->update($data);
        } else {
            $suratTugas = SuratTugas::create($data);
        }

        // Sync gurus
        $suratTugas->gurus()->sync($this->selectedGurus);

        $this->closeModal();
        session()->flash('message', 'Surat tugas berhasil ' . ($this->editingId ? 'diperbarui' : 'ditambahkan'));
    }

    public function closeModal(): void
    {
        $this->showFormModal = false;
        $this->editingId = null;
    }

    public function delete(int $id): void
    {
        SuratTugas::findOrFail($id)->delete();
        session()->flash('message', 'Surat tugas berhasil dihapus');
    }
}
