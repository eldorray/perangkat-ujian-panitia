<?php

namespace App\Livewire\Admin;

use App\Models\KegiatanUjian;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('POS Ujian')]
class PosUjianManagement extends Component
{
    public KegiatanUjian $kegiatanUjian;

    // Link referensi POS
    public string $posReferenceUrl = 'https://docs.google.com/document/d/1lM3O9cJuMqQzGhPn-LUYAUK9NXGRYU_k/edit?usp=drive_web&ouid=113561080338878785792&rtpof=true';

    public function mount(int $kegiatanUjianId): void
    {
        $this->kegiatanUjian = KegiatanUjian::with('tahunAjaran')->findOrFail($kegiatanUjianId);
    }

    public function render(): View
    {
        return view('livewire.admin.pos-ujian-management');
    }
}
