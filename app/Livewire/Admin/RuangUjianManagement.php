<?php

namespace App\Livewire\Admin;

use App\Models\RuangUjian;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Manajemen Ruang Ujian')]
class RuangUjianManagement extends Component
{
    use WithPagination;

    public string $search = '';
    public bool $showModal = false;
    public bool $showDeleteModal = false;
    public ?int $editingId = null;
    public ?int $deletingId = null;

    // Form fields
    public string $kode = '';
    public string $nama = '';
    public int $kapasitas = 0;

    protected function rules(): array
    {
        return [
            'kode' => 'required|string|max:20|unique:ruang_ujians,kode' . ($this->editingId ? ',' . $this->editingId : ''),
            'nama' => 'required|string|max:255',
            'kapasitas' => 'required|integer|min:1',
        ];
    }

    protected array $messages = [
        'kode.required' => 'Kode ruangan wajib diisi.',
        'kode.unique' => 'Kode ruangan sudah digunakan.',
        'nama.required' => 'Nama ruangan wajib diisi.',
        'kapasitas.required' => 'Kapasitas wajib diisi.',
        'kapasitas.min' => 'Kapasitas minimal 1.',
    ];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        $this->reset(['kode', 'nama', 'kapasitas', 'editingId']);
        $this->kapasitas = 20;
        $this->showModal = true;
    }

    public function edit(int $id): void
    {
        $ruangUjian = RuangUjian::findOrFail($id);
        $this->editingId = $ruangUjian->id;
        $this->kode = $ruangUjian->kode;
        $this->nama = $ruangUjian->nama;
        $this->kapasitas = $ruangUjian->kapasitas;
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'kode' => $this->kode,
            'nama' => $this->nama,
            'kapasitas' => $this->kapasitas,
        ];

        if ($this->editingId) {
            RuangUjian::findOrFail($this->editingId)->update($data);
            session()->flash('success', 'Ruang ujian berhasil diperbarui.');
        } else {
            RuangUjian::create($data);
            session()->flash('success', 'Ruang ujian berhasil ditambahkan.');
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
            RuangUjian::findOrFail($this->deletingId)->delete();
            session()->flash('success', 'Ruang ujian berhasil dihapus.');
        }
        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->showDeleteModal = false;
        $this->reset(['kode', 'nama', 'kapasitas', 'editingId', 'deletingId']);
        $this->resetValidation();
    }

    public function render(): View
    {
        $ruangUjians = RuangUjian::query()
            ->when($this->search, fn($q) => $q->where('nama', 'like', "%{$this->search}%")
                ->orWhere('kode', 'like', "%{$this->search}%"))
            ->orderBy('kode')
            ->paginate(10);

        return view('livewire.admin.ruang-ujian-management', compact('ruangUjians'));
    }
}
