<?php

namespace App\Livewire\Admin;

use App\Models\KegiatanUjian;
use App\Models\PenempatanRuangUjian;
use App\Models\RuangUjian;
use App\Models\SchoolSetting;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Daftar Hadir Peserta')]
class DaftarHadirManagement extends Component
{
    public KegiatanUjian $kegiatanUjian;
    
    // Selected room for viewing
    public ?int $selectedRuangId = null;

    public function mount(int $id): void
    {
        $this->kegiatanUjian = KegiatanUjian::with('tahunAjaran')->findOrFail($id);
    }

    public function selectRuang(int $ruangId): void
    {
        $this->selectedRuangId = $ruangId;
    }

    public function clearSelection(): void
    {
        $this->selectedRuangId = null;
    }

    public function render(): View
    {
        // Get all rooms with their placements for this kegiatan
        $ruangList = RuangUjian::orderBy('kode')->get();
        
        $ruangWithPenempatan = $ruangList->map(function ($ruang) {
            $penempatans = PenempatanRuangUjian::where('kegiatan_ujian_id', $this->kegiatanUjian->id)
                ->where('ruang_ujian_id', $ruang->id)
                ->with('siswa')
                ->orderBy('nomor_urut')
                ->get();
            
            return [
                'id' => $ruang->id,
                'kode' => $ruang->kode,
                'nama' => $ruang->nama,
                'kapasitas' => $ruang->kapasitas,
                'terisi' => $penempatans->count(),
                'penempatans' => $penempatans,
            ];
        })->filter(function ($ruang) {
            return $ruang['terisi'] > 0; // Only show rooms with placements
        });
        
        // Get data for selected room
        $daftarHadirData = null;
        if ($this->selectedRuangId) {
            $ruang = $ruangWithPenempatan->firstWhere('id', $this->selectedRuangId);
            if ($ruang) {
                $daftarHadirData = [
                    'ruang' => $ruang,
                    'penempatans' => $ruang['penempatans'],
                    'total_siswa' => $ruang['terisi'],
                    'schoolSettings' => SchoolSetting::getAllSettings(),
                ];
            }
        }

        return view('livewire.admin.daftar-hadir-management', compact(
            'ruangWithPenempatan',
            'daftarHadirData'
        ));
    }
}
