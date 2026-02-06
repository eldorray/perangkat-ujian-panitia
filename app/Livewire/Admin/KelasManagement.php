<?php

namespace App\Livewire\Admin;

use App\Models\Kelas;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Manajemen Kelas')]
class KelasManagement extends Component
{
    use WithPagination;

    public string $search = '';
    public bool $showModal = false;
    public bool $showDeleteModal = false;
    public ?int $editingId = null;
    public ?int $deletingId = null;

    // Form fields
    public string $nama = '';
    public string $tingkat = 'X';

    protected array $rules = [
        'nama' => 'required|string|max:255',
        'tingkat' => 'required|string|max:10',
    ];

    protected array $messages = [
        'nama.required' => 'Nama kelas wajib diisi.',
        'tingkat.required' => 'Tingkat kelas wajib dipilih.',
    ];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        $this->reset(['nama', 'tingkat', 'editingId']);
        $this->tingkat = 'X';
        $this->showModal = true;
    }

    public function edit(int $id): void
    {
        $kelas = Kelas::findOrFail($id);
        $this->editingId = $kelas->id;
        $this->nama = $kelas->nama;
        $this->tingkat = $kelas->tingkat;
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'nama' => $this->nama,
            'tingkat' => $this->tingkat,
        ];

        if ($this->editingId) {
            Kelas::findOrFail($this->editingId)->update($data);
            session()->flash('success', 'Kelas berhasil diperbarui.');
        } else {
            Kelas::create($data);
            session()->flash('success', 'Kelas berhasil ditambahkan.');
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
            $kelas = Kelas::findOrFail($this->deletingId);
            if ($kelas->siswas_count > 0) {
                session()->flash('error', 'Tidak dapat menghapus kelas yang memiliki siswa.');
            } else {
                $kelas->delete();
                session()->flash('success', 'Kelas berhasil dihapus.');
            }
        }
        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->showDeleteModal = false;
        $this->reset(['nama', 'tingkat', 'editingId', 'deletingId']);
        $this->resetValidation();
    }

    public function render(): View
    {
        $kelasList = Kelas::query()
            ->when($this->search, fn($q) => $q->where('nama', 'like', "%{$this->search}%")
                ->orWhere('tingkat', 'like', "%{$this->search}%"))
            ->orderBy('tingkat')
            ->orderBy('nama')
            ->paginate(10);

        return view('livewire.admin.kelas-management', compact('kelasList'));
    }
}
