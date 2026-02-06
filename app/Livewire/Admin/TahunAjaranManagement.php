<?php

namespace App\Livewire\Admin;

use App\Models\TahunAjaran;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Manajemen Tahun Ajaran')]
class TahunAjaranManagement extends Component
{
    use WithPagination;

    public string $search = '';
    public bool $showModal = false;
    public bool $showDeleteModal = false;
    public ?int $editingId = null;
    public ?int $deletingId = null;

    // Form fields
    public string $nama = '';
    public string $semester = 'Ganjil';
    public bool $is_active = false;
    public ?string $tanggal_mulai = null;
    public ?string $tanggal_selesai = null;

    protected array $rules = [
        'nama' => 'required|string|max:255',
        'semester' => 'required|in:Ganjil,Genap',
        'is_active' => 'boolean',
        'tanggal_mulai' => 'nullable|date',
        'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
    ];

    protected array $messages = [
        'nama.required' => 'Nama tahun ajaran wajib diisi.',
        'semester.required' => 'Semester wajib dipilih.',
        'semester.in' => 'Semester harus Ganjil atau Genap.',
        'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus setelah tanggal mulai.',
    ];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        $this->reset(['nama', 'semester', 'is_active', 'tanggal_mulai', 'tanggal_selesai', 'editingId']);
        $this->semester = 'Ganjil';
        $this->showModal = true;
    }

    public function edit(int $id): void
    {
        $tahunAjaran = TahunAjaran::findOrFail($id);
        $this->editingId = $tahunAjaran->id;
        $this->nama = $tahunAjaran->nama;
        $this->semester = $tahunAjaran->semester;
        $this->is_active = $tahunAjaran->is_active;
        $this->tanggal_mulai = $tahunAjaran->tanggal_mulai?->format('Y-m-d');
        $this->tanggal_selesai = $tahunAjaran->tanggal_selesai?->format('Y-m-d');
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'nama' => $this->nama,
            'semester' => $this->semester,
            'is_active' => $this->is_active,
            'tanggal_mulai' => $this->tanggal_mulai ?: null,
            'tanggal_selesai' => $this->tanggal_selesai ?: null,
        ];

        // Jika diaktifkan, nonaktifkan yang lain
        if ($this->is_active) {
            TahunAjaran::where('id', '!=', $this->editingId)->update(['is_active' => false]);
        }

        if ($this->editingId) {
            TahunAjaran::findOrFail($this->editingId)->update($data);
            session()->flash('success', 'Tahun ajaran berhasil diperbarui.');
        } else {
            TahunAjaran::create($data);
            session()->flash('success', 'Tahun ajaran berhasil ditambahkan.');
        }

        $this->closeModal();
    }

    public function confirmDelete(int $id): void
    {
        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        if ($this->deletingId) {
            TahunAjaran::findOrFail($this->deletingId)->delete();
            session()->flash('success', 'Tahun ajaran berhasil dihapus.');
        }
        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->showDeleteModal = false;
        $this->reset(['nama', 'semester', 'is_active', 'tanggal_mulai', 'tanggal_selesai', 'editingId', 'deletingId']);
        $this->resetValidation();
    }

    public function render(): View
    {
        $tahunAjarans = TahunAjaran::query()
            ->when($this->search, fn($q) => $q->where('nama', 'like', "%{$this->search}%")
                ->orWhere('semester', 'like', "%{$this->search}%"))
            ->orderByDesc('is_active')
            ->orderByDesc('nama')
            ->paginate(10);

        return view('livewire.admin.tahun-ajaran-management', compact('tahunAjarans'));
    }
}
