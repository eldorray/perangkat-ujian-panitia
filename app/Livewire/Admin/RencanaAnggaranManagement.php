<?php

namespace App\Livewire\Admin;

use App\Models\KegiatanUjian;
use App\Models\RencanaAnggaran;
use App\Models\RencanaAnggaranItem;
use App\Models\SchoolSetting;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Rencana Anggaran')]
class RencanaAnggaranManagement extends Component
{
    public KegiatanUjian $kegiatanUjian;
    public ?RencanaAnggaran $rencanaAnggaran = null;
    public ?string $addingKategori = null; // Track which category is adding
    public bool $showPreview = false;
    public ?int $editingItemId = null;

    // Form fields for header
    public string $sumber_anggaran = '';
    public int $jumlah_siswa = 0;
    public string $label_siswa_non_k = 'Pemasukan siswa non K';
    public float $iuran_siswa_non_k = 0;
    public int $jumlah_siswa_non_k = 0;
    public string $label_siswa_k = 'Pemasukan siswa';
    public int $jumlah_siswa_k = 0;
    public float $iuran_siswa_k = 0;
    public string $tempat = '';
    public ?string $tanggal_dokumen = null;
    public string $nama_ketua = '';
    public string $nip_ketua = '';
    public string $nama_bendahara = '';
    public string $nip_bendahara = '';
    public string $nama_kepala_sekolah = '';
    public string $nip_kepala_sekolah = '';

    // Item form fields
    public string $item_kategori = 'pengeluaran';
    public string $item_nama = '';
    public ?int $item_jumlah = null;
    public string $item_operator = 'x';
    public ?int $item_jumlah2 = null;
    public string $item_operator2 = '';
    public ?int $item_jumlah3 = null;
    public ?float $item_harga_satuan = null;
    public float $item_subtotal = 0;

    public function mount(int $kegiatanUjianId): void
    {
        $this->kegiatanUjian = KegiatanUjian::with('tahunAjaran')->findOrFail($kegiatanUjianId);
        $this->rencanaAnggaran = RencanaAnggaran::where('kegiatan_ujian_id', $kegiatanUjianId)->first();

        if ($this->rencanaAnggaran) {
            $this->loadFromModel();
        } else {
            $this->loadDefaults();
        }
    }

    protected function loadFromModel(): void
    {
        $this->sumber_anggaran = $this->rencanaAnggaran->sumber_anggaran ?? '';
        $this->jumlah_siswa = $this->rencanaAnggaran->jumlah_siswa ?? 0;
        $this->label_siswa_non_k = $this->rencanaAnggaran->label_siswa_non_k ?? 'Pemasukan siswa non K';
        $this->iuran_siswa_non_k = (float)$this->rencanaAnggaran->iuran_siswa_non_k ?? 0;
        $this->jumlah_siswa_non_k = $this->rencanaAnggaran->jumlah_siswa_non_k ?? 0;
        $this->label_siswa_k = $this->rencanaAnggaran->label_siswa_k ?? 'Pemasukan siswa';
        $this->jumlah_siswa_k = $this->rencanaAnggaran->jumlah_siswa_k ?? 0;
        $this->iuran_siswa_k = (float)$this->rencanaAnggaran->iuran_siswa_k ?? 0;
        $this->tempat = $this->rencanaAnggaran->tempat ?? '';
        $this->tanggal_dokumen = $this->rencanaAnggaran->tanggal_dokumen?->format('Y-m-d');
        $this->nama_ketua = $this->rencanaAnggaran->nama_ketua ?? '';
        $this->nip_ketua = $this->rencanaAnggaran->nip_ketua ?? '';
        $this->nama_bendahara = $this->rencanaAnggaran->nama_bendahara ?? '';
        $this->nip_bendahara = $this->rencanaAnggaran->nip_bendahara ?? '';
        $this->nama_kepala_sekolah = $this->rencanaAnggaran->nama_kepala_sekolah ?? '';
        $this->nip_kepala_sekolah = $this->rencanaAnggaran->nip_kepala_sekolah ?? '';
    }

    protected function loadDefaults(): void
    {
        $schoolSettings = SchoolSetting::first();
        $this->tempat = $schoolSettings?->kabupaten ?? 'Tangerang';
        $this->sumber_anggaran = 'Dari Siswa KK Non Kota ' . ($schoolSettings?->kabupaten ?? 'Tangerang') . ' Berdasarkan Rapat Komite Dengan Pihak Sekolah Dan Dari BOSP';
        $this->tanggal_dokumen = now()->format('Y-m-d');
        $this->nama_kepala_sekolah = $schoolSettings?->nama_kepala_sekolah ?? '';
        $this->nip_kepala_sekolah = $schoolSettings?->nip_kepala_sekolah ?? '';
    }

    public function save(): void
    {
        $subtotalNonK = $this->jumlah_siswa_non_k * $this->iuran_siswa_non_k;
        $subtotalK = $this->jumlah_siswa_k * $this->iuran_siswa_k;
        $totalPemasukan = $subtotalNonK + $subtotalK;

        $data = [
            'kegiatan_ujian_id' => $this->kegiatanUjian->id,
            'sumber_anggaran' => $this->sumber_anggaran,
            'jumlah_siswa' => $this->jumlah_siswa,
            'label_siswa_non_k' => $this->label_siswa_non_k,
            'iuran_siswa_non_k' => $this->iuran_siswa_non_k,
            'jumlah_siswa_non_k' => $this->jumlah_siswa_non_k,
            'label_siswa_k' => $this->label_siswa_k,
            'jumlah_siswa_k' => $this->jumlah_siswa_k,
            'iuran_siswa_k' => $this->iuran_siswa_k,
            'total_pemasukan' => $totalPemasukan,
            'tempat' => $this->tempat,
            'tanggal_dokumen' => $this->tanggal_dokumen,
            'nama_ketua' => $this->nama_ketua,
            'nip_ketua' => $this->nip_ketua,
            'nama_bendahara' => $this->nama_bendahara,
            'nip_bendahara' => $this->nip_bendahara,
            'nama_kepala_sekolah' => $this->nama_kepala_sekolah,
            'nip_kepala_sekolah' => $this->nip_kepala_sekolah,
        ];

        if ($this->rencanaAnggaran) {
            $this->rencanaAnggaran->update($data);
        } else {
            $this->rencanaAnggaran = RencanaAnggaran::create($data);
        }

        session()->flash('success', 'Rencana anggaran berhasil disimpan.');
    }

    public function openItemModal(string $kategori = 'pengeluaran'): void
    {
        // Auto-save if rencanaAnggaran doesn't exist yet
        if (!$this->rencanaAnggaran) {
            $this->save();
        }
        
        $this->resetItemForm();
        $this->item_kategori = $kategori;
        $this->addingKategori = $kategori;
    }

    public function editItem(int $itemId): void
    {
        $item = RencanaAnggaranItem::find($itemId);
        if ($item) {
            $this->editingItemId = $itemId;
            $this->item_kategori = $item->kategori;
            $this->item_nama = $item->nama_item;
            $this->item_jumlah = $item->jumlah;
            $this->item_operator = $item->operator ?? 'x';
            $this->item_jumlah2 = $item->jumlah2;
            $this->item_operator2 = $item->operator2 ?? '';
            $this->item_jumlah3 = $item->jumlah3;
            $this->item_harga_satuan = (float)$item->harga_satuan;
            $this->item_subtotal = (float)$item->subtotal;
            $this->addingKategori = $item->kategori;
        }
    }

    public function saveItem(): void
    {
        $this->validate([
            'item_nama' => 'required|string|max:255',
        ]);

        // Ensure rencanaAnggaran exists
        if (!$this->rencanaAnggaran) {
            $this->save();
        }

        // Calculate subtotal
        $subtotal = 1;
        if ($this->item_jumlah) {
            $subtotal = $this->item_jumlah;
        }
        if ($this->item_jumlah2 !== null && $this->item_jumlah2 > 0) {
            $subtotal *= $this->item_jumlah2;
        }
        if ($this->item_jumlah3 !== null && $this->item_jumlah3 > 0) {
            $subtotal *= $this->item_jumlah3;
        }
        if ($this->item_harga_satuan) {
            $subtotal *= $this->item_harga_satuan;
        }

        // If item_subtotal is manually set, use that instead
        if ($this->item_subtotal > 0 && !$this->item_jumlah && !$this->item_harga_satuan) {
            $subtotal = $this->item_subtotal;
        }

        $data = [
            'rencana_anggaran_id' => $this->rencanaAnggaran->id,
            'kategori' => $this->item_kategori,
            'nama_item' => $this->item_nama,
            'jumlah' => $this->item_jumlah,
            'operator' => $this->item_operator ?: null,
            'jumlah2' => $this->item_jumlah2,
            'operator2' => $this->item_operator2 ?: null,
            'jumlah3' => $this->item_jumlah3,
            'harga_satuan' => $this->item_harga_satuan,
            'subtotal' => $subtotal,
            'sort_order' => $this->editingItemId 
                ? RencanaAnggaranItem::find($this->editingItemId)->sort_order 
                : RencanaAnggaranItem::where('rencana_anggaran_id', $this->rencanaAnggaran->id)
                    ->where('kategori', $this->item_kategori)->max('sort_order') + 1,
        ];

        if ($this->editingItemId) {
            RencanaAnggaranItem::where('id', $this->editingItemId)->update($data);
            session()->flash('success', 'Item berhasil diperbarui.');
        } else {
            RencanaAnggaranItem::create($data);
            session()->flash('success', 'Item berhasil ditambahkan.');
        }

        $this->closeItemModal();
    }

    public function deleteItem(int $itemId): void
    {
        RencanaAnggaranItem::destroy($itemId);
        session()->flash('success', 'Item berhasil dihapus.');
    }

    public function closeItemModal(): void
    {
        $this->addingKategori = null;
        $this->resetItemForm();
    }

    protected function resetItemForm(): void
    {
        $this->editingItemId = null;
        $this->item_kategori = 'pengeluaran';
        $this->item_nama = '';
        $this->item_jumlah = null;
        $this->item_operator = 'x';
        $this->item_jumlah2 = null;
        $this->item_operator2 = '';
        $this->item_jumlah3 = null;
        $this->item_harga_satuan = null;
        $this->item_subtotal = 0;
    }

    public function togglePreview(): void
    {
        $this->showPreview = !$this->showPreview;
    }

    public function getKategoriLabel(string $kategori): string
    {
        return match($kategori) {
            'pengeluaran' => 'Pengeluaran',
            'insentif_panitia' => 'Insentif Panitia',
            'insentif_struktural' => 'Insentif Struktural',
            'operasional' => 'Operasional',
            default => $kategori,
        };
    }

    public function render(): View
    {
        $items = $this->rencanaAnggaran ? $this->rencanaAnggaran->items()->get()->groupBy('kategori') : collect();
        $schoolSettings = SchoolSetting::getAllSettings();

        // Calculate totals
        $subtotalPemasukanNonK = $this->jumlah_siswa_non_k * $this->iuran_siswa_non_k;
        $subtotalPemasukanK = $this->jumlah_siswa_k * $this->iuran_siswa_k;
        $totalPemasukan = $subtotalPemasukanNonK + $subtotalPemasukanK;
        
        $totalPengeluaran = $this->rencanaAnggaran ? $this->rencanaAnggaran->items()->sum('subtotal') : 0;
        $saldo = $totalPemasukan - $totalPengeluaran;

        return view('livewire.admin.rencana-anggaran-management', compact(
            'items', 
            'schoolSettings',
            'subtotalPemasukanNonK',
            'subtotalPemasukanK',
            'totalPemasukan',
            'totalPengeluaran',
            'saldo'
        ));
    }
}
