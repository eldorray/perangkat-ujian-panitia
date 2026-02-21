<?php

namespace App\Livewire\Admin;

use App\Models\KegiatanUjian;
use App\Models\TahunAjaran;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Kegiatan Ujian')]
class KegiatanUjianManagement extends Component
{
    use WithPagination;

    public string $search = '';
    public bool $showModal = false;
    public bool $showDeleteModal = false;
    public ?int $editingId = null;
    public ?int $deletingId = null;

    // Form fields
    public string $nama_ujian = '';
    public ?int $tahun_ajaran_id = null;
    public string $sk_number = '';
    public string $keterangan = '';
    public string $ketua_panitia = '';
    public string $nip_ketua_panitia = '';
    public ?string $tanggal_dokumen = null;

    // Lock/Unlock properties
    public bool $showLockModal = false;
    public bool $showUnlockModal = false;
    public ?int $lockingId = null;
    public ?int $unlockingId = null;
    public string $pin = '';
    public string $confirmPin = '';
    public string $unlockPin = '';

    protected function rules(): array
    {
        return [
            'nama_ujian' => 'required|string|max:255',
            'tahun_ajaran_id' => 'required|exists:tahun_ajarans,id',
            'sk_number' => 'nullable|string|max:100',
            'keterangan' => 'nullable|string|max:500',
            'ketua_panitia' => 'nullable|string|max:255',
            'nip_ketua_panitia' => 'nullable|string|max:50',
            'tanggal_dokumen' => 'nullable|date',
        ];
    }

    protected array $messages = [
        'nama_ujian.required' => 'Nama ujian wajib diisi.',
        'tahun_ajaran_id.required' => 'Tahun ajaran wajib dipilih.',
        'tahun_ajaran_id.exists' => 'Tahun ajaran tidak valid.',
        'pin.required' => 'PIN wajib diisi.',
        'pin.digits' => 'PIN harus 6 digit angka.',
        'confirmPin.same' => 'Konfirmasi PIN tidak cocok.',
        'unlockPin.required' => 'PIN wajib diisi.',
        'unlockPin.digits' => 'PIN harus 6 digit angka.',
    ];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        $this->reset(['nama_ujian', 'tahun_ajaran_id', 'sk_number', 'keterangan', 'ketua_panitia', 'nip_ketua_panitia', 'tanggal_dokumen', 'editingId']);
        $this->showModal = true;
    }

    public function edit(int $id): void
    {
        $kegiatan = KegiatanUjian::findOrFail($id);
        
        // Prevent editing if locked
        if ($kegiatan->is_locked) {
            session()->flash('error', 'Kegiatan ujian ini terkunci. Buka kunci terlebih dahulu untuk mengedit.');
            return;
        }
        
        $this->editingId = $kegiatan->id;
        $this->nama_ujian = $kegiatan->nama_ujian;
        $this->tahun_ajaran_id = $kegiatan->tahun_ajaran_id;
        $this->sk_number = $kegiatan->sk_number ?? '';
        $this->keterangan = $kegiatan->keterangan ?? '';
        $this->ketua_panitia = $kegiatan->ketua_panitia ?? '';
        $this->nip_ketua_panitia = $kegiatan->nip_ketua_panitia ?? '';
        $this->tanggal_dokumen = $kegiatan->tanggal_dokumen?->format('Y-m-d');
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'nama_ujian' => $this->nama_ujian,
            'tahun_ajaran_id' => $this->tahun_ajaran_id,
            'sk_number' => $this->sk_number ?: null,
            'keterangan' => $this->keterangan ?: null,
            'ketua_panitia' => $this->ketua_panitia ?: null,
            'nip_ketua_panitia' => $this->nip_ketua_panitia ?: null,
            'tanggal_dokumen' => $this->tanggal_dokumen ?: null,
        ];

        if ($this->editingId) {
            KegiatanUjian::findOrFail($this->editingId)->update($data);
            session()->flash('success', 'Kegiatan ujian berhasil diperbarui.');
        } else {
            KegiatanUjian::create($data);
            session()->flash('success', 'Kegiatan ujian berhasil ditambahkan.');
        }

        $this->closeModal();
    }

    public function confirmDelete(int $id): void
    {
        $kegiatan = KegiatanUjian::findOrFail($id);
        
        // Prevent deleting if locked
        if ($kegiatan->is_locked) {
            session()->flash('error', 'Kegiatan ujian ini terkunci. Buka kunci terlebih dahulu untuk menghapus.');
            return;
        }
        
        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        if ($this->deletingId) {
            KegiatanUjian::findOrFail($this->deletingId)->delete();
            session()->flash('success', 'Kegiatan ujian berhasil dihapus.');
        }
        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    // Lock functionality
    public function openLockModal(int $id): void
    {
        $kegiatan = KegiatanUjian::findOrFail($id);
        
        if ($kegiatan->is_locked) {
            session()->flash('error', 'Kegiatan ujian ini sudah terkunci.');
            return;
        }
        
        $this->lockingId = $id;
        $this->pin = '';
        $this->confirmPin = '';
        $this->showLockModal = true;
    }

    public function lock(): void
    {
        $this->validate([
            'pin' => 'required|digits:6',
            'confirmPin' => 'required|same:pin',
        ]);

        $kegiatan = KegiatanUjian::findOrFail($this->lockingId);
        $kegiatan->lock($this->pin);

        session()->flash('success', 'Kegiatan ujian berhasil dikunci.');
        $this->closeLockModal();
    }

    public function openUnlockModal(int $id): void
    {
        $kegiatan = KegiatanUjian::findOrFail($id);
        
        if (!$kegiatan->is_locked) {
            session()->flash('error', 'Kegiatan ujian ini tidak terkunci.');
            return;
        }
        
        $this->unlockingId = $id;
        $this->unlockPin = '';
        $this->showUnlockModal = true;
    }

    public function unlock(): void
    {
        $this->validate([
            'unlockPin' => 'required|digits:6',
        ]);

        $kegiatan = KegiatanUjian::findOrFail($this->unlockingId);
        
        if ($kegiatan->unlock($this->unlockPin)) {
            session()->flash('success', 'Kegiatan ujian berhasil dibuka kuncinya.');
            $this->closeLockModal();
        } else {
            $this->addError('unlockPin', 'PIN yang dimasukkan salah.');
        }
    }

    public function closeLockModal(): void
    {
        $this->showLockModal = false;
        $this->showUnlockModal = false;
        $this->reset(['lockingId', 'unlockingId', 'pin', 'confirmPin', 'unlockPin']);
        $this->resetValidation(['pin', 'confirmPin', 'unlockPin']);
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->showDeleteModal = false;
        $this->reset(['nama_ujian', 'tahun_ajaran_id', 'sk_number', 'keterangan', 'ketua_panitia', 'nip_ketua_panitia', 'tanggal_dokumen', 'editingId', 'deletingId']);
        $this->resetValidation();
    }

    public function render(): View
    {
        $kegiatanUjians = KegiatanUjian::query()
            ->with('tahunAjaran')
            ->when($this->search, fn($q) => $q->where('nama_ujian', 'like', "%{$this->search}%")
                ->orWhere('sk_number', 'like', "%{$this->search}%"))
            ->orderByDesc('created_at')
            ->paginate(10);

        $tahunAjarans = TahunAjaran::orderByDesc('nama')->get();

        return view('livewire.admin.kegiatan-ujian-management', compact('kegiatanUjians', 'tahunAjarans'));
    }
}
