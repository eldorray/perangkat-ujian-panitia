<div>
    <x-slot name="header">Rencana Anggaran</x-slot>

    <!-- Breadcrumb -->
    <div class="mb-6">
        <nav class="flex items-center gap-2 text-sm text-gray-500">
            <a href="{{ route('admin.kegiatan-ujian') }}" class="hover:text-gray-700" wire:navigate>Kegiatan Ujian</a>
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            <a href="{{ route('admin.kegiatan-ujian.perangkat', $kegiatanUjian->id) }}" class="hover:text-gray-700"
                wire:navigate>Perangkat</a>
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            <span class="font-medium text-gray-900">Rencana Anggaran</span>
        </nav>
    </div>

    <!-- Header Info -->
    <div class="mb-6 p-4 rounded-xl bg-gray-50 border border-gray-200 print:hidden">
        <h2 class="text-lg font-semibold text-gray-900">{{ $kegiatanUjian->nama_ujian }}</h2>
        <p class="text-sm text-gray-500 mt-1">{{ $kegiatanUjian->tahunAjaran->nama }} -
            {{ $kegiatanUjian->tahunAjaran->semester }}</p>
    </div>

    @if ($showPreview)
        <!-- Preview Mode -->
        <div class="mb-4 print:hidden">
            <button wire:click="togglePreview" class="btn btn-secondary">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Edit
            </button>
        </div>

        <!-- Printable Area -->
        <div class="bg-white" id="anggaran-print">
            <div class="p-6 border-b border-gray-200 flex items-center justify-between print:hidden">
                <h3 class="text-lg font-semibold text-gray-900">Preview Rencana Anggaran</h3>
                <button onclick="window.print()" class="btn btn-primary">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                        </path>
                    </svg>
                    Cetak
                </button>
            </div>

            <div class="p-8">
                <!-- Kop Surat -->
                <div class="flex items-center gap-4 border-b-4 border-double border-black pb-3 mb-4">
                    <div class="w-16 h-16 flex-shrink-0">
                        @if (!empty($schoolSettings['logo']))
                            <img src="{{ asset('storage/' . $schoolSettings['logo']) }}" alt="Logo"
                                class="w-full h-full object-contain">
                        @else
                            <svg viewBox="0 0 24 24" fill="#666" class="w-full h-full">
                                <path
                                    d="M12 3L1 9l4 2.18v6L12 21l7-3.82v-6l2-1.09V17h2V9L12 3zm6.82 6L12 12.72 5.18 9 12 5.28 18.82 9zM17 15.99l-5 2.73-5-2.73v-3.72L12 15l5-2.73v3.72z" />
                            </svg>
                        @endif
                    </div>
                    <div class="flex-1 text-center">
                        <div class="text-xs font-bold">KEMENTERIAN AGAMA REPUBLIK INDONESIA</div>
                        <div class="text-lg font-bold">
                            {{ strtoupper($schoolSettings['nama_sekolah'] ?? 'NAMA SEKOLAH') }}
                        </div>
                        <div class="text-xs">Alamat: {{ $schoolSettings['alamat'] ?? '' }}
                            {{ $schoolSettings['kelurahan'] ?? '' }}
                            {{ $schoolSettings['kecamatan'] ?? '' }}</div>
                        <div class="text-xs">
                            {{ strtoupper($schoolSettings['kabupaten'] ?? '') }}
                            {{ $schoolSettings['kode_pos'] ?? '' }}</div>
                    </div>
                </div>

                <!-- Title -->
                <div class="text-center mb-6">
                    <h1 class="text-base font-bold underline">RENCANA ANGGARAN</h1>
                    <h2 class="text-sm font-bold">{{ strtoupper($kegiatanUjian->nama_ujian) }}</h2>
                    <h3 class="text-sm">Tahun Pelajaran {{ $kegiatanUjian->tahunAjaran->nama }}</h3>
                </div>

                <!-- A. Sumber Anggaran -->
                <div class="mb-4 text-sm">
                    <p class="font-bold">A. Sumber Anggaran</p>
                    <p class="ml-4">{{ $sumber_anggaran }}</p>
                </div>

                <!-- B. Anggaran -->
                <div class="mb-4 text-sm">
                    <p class="font-bold">B. Anggaran</p>
                    <table class="ml-4 w-full">
                        <tr>
                            <td class="py-1 w-48">Jumlah siswa {{ $jumlah_siswa }}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="py-1">{{ $label_siswa_non_k }}</td>
                            <td class="text-right w-12">{{ $jumlah_siswa_non_k }}</td>
                            <td class="w-4"></td>
                            <td class="text-right w-24"></td>
                            <td class="w-8"></td>
                            <td class="text-right w-32">{{ number_format($subtotalPemasukanNonK, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="py-1">I. {{ $label_siswa_k }}</td>
                            <td class="text-right">{{ $jumlah_siswa_k }}</td>
                            <td class="text-center">X</td>
                            <td class="text-right">{{ number_format($iuran_siswa_k, 0, ',', '.') }}</td>
                            <td class="text-center">=</td>
                            <td class="text-right">{{ number_format($subtotalPemasukanK, 0, ',', '.') }}</td>
                        </tr>
                        <tr class="font-bold">
                            <td colspan="4"></td>
                            <td class="text-center">=</td>
                            <td class="text-right border-t border-black">
                                {{ number_format($totalPemasukan, 0, ',', '.') }}</td>
                        </tr>
                    </table>
                </div>

                <!-- II. Pengeluaran -->
                <div class="mb-4 text-sm">
                    <p class="font-bold ml-4">II. Pengeluaran :</p>
                    <table class="ml-8 w-full">
                        @if (isset($items['pengeluaran']))
                            @foreach ($items['pengeluaran'] as $item)
                                <tr>
                                    <td class="py-0.5 w-48">{{ $item->nama_item }}</td>
                                    <td class="text-right w-12">{{ $item->jumlah }}</td>
                                    <td class="text-center w-6">{{ $item->operator }}</td>
                                    <td class="text-right w-16">{{ $item->jumlah2 }}</td>
                                    <td class="text-center w-6">{{ $item->operator2 ?: '' }}</td>
                                    <td class="text-right w-16">{{ $item->jumlah3 }}</td>
                                    <td class="text-right w-20">
                                        {{ $item->harga_satuan ? number_format($item->harga_satuan, 0, ',', '.') : '' }}
                                    </td>
                                    <td class="text-center w-6">=</td>
                                    <td class="text-right w-28">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </table>
                </div>

                <!-- III. Insentif Panitia -->
                <div class="mb-4 text-sm">
                    <p class="font-bold ml-4">III. Insentif Panitia</p>
                    <table class="ml-8 w-full">
                        @if (isset($items['insentif_panitia']))
                            @foreach ($items['insentif_panitia'] as $item)
                                <tr>
                                    <td class="py-0.5 w-48">{{ $item->nama_item }}</td>
                                    <td class="text-right w-12">{{ $item->jumlah }}</td>
                                    <td class="text-center w-6">{{ $item->operator }}</td>
                                    <td class="text-right w-16">{{ $item->jumlah2 }}</td>
                                    <td class="text-center w-6">{{ $item->operator2 ?: '' }}</td>
                                    <td class="text-right w-16">{{ $item->jumlah3 }}</td>
                                    <td class="text-right w-20">
                                        {{ $item->harga_satuan ? number_format($item->harga_satuan, 0, ',', '.') : '' }}
                                    </td>
                                    <td class="text-center w-6">=</td>
                                    <td class="text-right w-28">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </table>
                </div>

                <!-- IV. Insentif Struktural -->
                <div class="mb-4 text-sm">
                    <p class="font-bold ml-4">IV. Insentif Struktural</p>
                    <table class="ml-8 w-full">
                        @if (isset($items['insentif_struktural']))
                            @foreach ($items['insentif_struktural'] as $item)
                                <tr>
                                    <td class="py-0.5 w-48">{{ $item->nama_item }}</td>
                                    <td class="text-right w-12">{{ $item->jumlah }}</td>
                                    <td class="text-center w-6">{{ $item->operator }}</td>
                                    <td class="text-right w-16">{{ $item->jumlah2 }}</td>
                                    <td class="text-center w-6">{{ $item->operator2 ?: '' }}</td>
                                    <td class="text-right w-16">{{ $item->jumlah3 }}</td>
                                    <td class="text-right w-20">
                                        {{ $item->harga_satuan ? number_format($item->harga_satuan, 0, ',', '.') : '' }}
                                    </td>
                                    <td class="text-center w-6">=</td>
                                    <td class="text-right w-28">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </table>
                </div>

                <!-- Operasional -->
                <div class="mb-4 text-sm">
                    <table class="ml-8 w-full">
                        @if (isset($items['operasional']))
                            @foreach ($items['operasional'] as $item)
                                <tr>
                                    <td class="py-0.5 w-48">{{ $item->nama_item }}</td>
                                    <td class="text-right w-12">{{ $item->jumlah }}</td>
                                    <td class="text-center w-6">{{ $item->operator }}</td>
                                    <td class="text-right w-16">{{ $item->jumlah2 }}</td>
                                    <td class="text-center w-6">{{ $item->operator2 ?: '' }}</td>
                                    <td class="text-right w-16">{{ $item->jumlah3 }}</td>
                                    <td class="text-right w-20">
                                        {{ $item->harga_satuan ? number_format($item->harga_satuan, 0, ',', '.') : '' }}
                                    </td>
                                    <td class="text-center w-6">=</td>
                                    <td class="text-right w-28">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </table>
                </div>

                <!-- Jumlah & Saldo -->
                <div class="mb-6 text-sm">
                    <table class="ml-4 w-full font-bold">
                        <tr>
                            <td class="py-1">IV Jumlah poin II dan III</td>
                            <td class="text-center w-6">=</td>
                            <td class="text-right w-32">{{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="py-1">Saldo</td>
                            <td class="text-center">=</td>
                            <td class="text-right">{{ number_format($saldo, 0, ',', '.') }}</td>
                        </tr>
                    </table>
                </div>

                <!-- Tanda Tangan -->
                <div class="text-sm mt-8">
                    <div class="text-right mb-4">
                        <p>{{ $tempat }},
                            {{ $tanggal_dokumen ? \Carbon\Carbon::parse($tanggal_dokumen)->translatedFormat('d F Y') : '' }}
                        </p>
                        <p>Panitia {{ $kegiatanUjian->tahunAjaran->semester }}</p>
                    </div>

                    <div class="flex justify-between mt-8">
                        <div class="text-center">
                            <p class="underline font-bold">{{ $nama_ketua ?: '................................' }}</p>
                            <p>Ketua</p>
                        </div>
                        <div class="text-center">
                            <p class="underline font-bold">{{ $nama_bendahara ?: '................................' }}
                            </p>
                            <p>Bendahara</p>
                        </div>
                    </div>

                    <div class="text-center mt-8">
                        <p class="mb-4">Mengetahui,</p>
                        <p class="underline font-bold mt-12">
                            {{ $nama_kepala_sekolah ?: '................................' }}</p>
                        <p>Ketua Panitia</p>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Edit Mode -->
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Form Pemasukan -->
            <div class="card">
                <div class="p-4 border-b border-gray-200">
                    <h3 class="font-semibold text-gray-900">Data Pemasukan</h3>
                </div>
                <div class="p-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sumber Anggaran</label>
                        <textarea wire:model="sumber_anggaran" rows="2" class="input w-full"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Siswa Total</label>
                        <input type="number" wire:model="jumlah_siswa" class="input w-full">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Label Siswa Non-K</label>
                            <input type="text" wire:model="label_siswa_non_k" class="input w-full">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Siswa Non-K</label>
                            <input type="number" wire:model="jumlah_siswa_non_k" class="input w-full">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Iuran per Siswa Non-K</label>
                        <input type="number" wire:model="iuran_siswa_non_k" class="input w-full">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Label Siswa K</label>
                            <input type="text" wire:model="label_siswa_k" class="input w-full">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Siswa K</label>
                            <input type="number" wire:model="jumlah_siswa_k" class="input w-full">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Iuran per Siswa K</label>
                        <input type="number" wire:model="iuran_siswa_k" class="input w-full">
                    </div>

                    <div class="pt-4 border-t">
                        <p class="text-sm"><strong>Subtotal Non-K:</strong> Rp
                            {{ number_format($subtotalPemasukanNonK, 0, ',', '.') }}</p>
                        <p class="text-sm"><strong>Subtotal K:</strong> Rp
                            {{ number_format($subtotalPemasukanK, 0, ',', '.') }}</p>
                        <p class="text-lg font-bold text-green-600"><strong>Total Pemasukan:</strong> Rp
                            {{ number_format($totalPemasukan, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <!-- Form Tanda Tangan -->
            <div class="card">
                <div class="p-4 border-b border-gray-200">
                    <h3 class="font-semibold text-gray-900">Tanda Tangan</h3>
                </div>
                <div class="p-4 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tempat</label>
                            <input type="text" wire:model="tempat" class="input w-full">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                            <input type="date" wire:model="tanggal_dokumen" class="input w-full">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Ketua</label>
                            <input type="text" wire:model="nama_ketua" class="input w-full">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">NIP Ketua</label>
                            <input type="text" wire:model="nip_ketua" class="input w-full">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Bendahara</label>
                            <input type="text" wire:model="nama_bendahara" class="input w-full">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">NIP Bendahara</label>
                            <input type="text" wire:model="nip_bendahara" class="input w-full">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Ketua Panitia</label>
                            <input type="text" wire:model="nama_kepala_sekolah" class="input w-full">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">NIP Ketua Panitia</label>
                            <input type="text" wire:model="nip_kepala_sekolah" class="input w-full">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Items Section -->
        <div class="mt-6">
            @foreach (['pengeluaran', 'insentif_panitia', 'insentif_struktural', 'operasional'] as $kategori)
                <div class="card mb-4">
                    <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                        <h3 class="font-semibold text-gray-900">{{ $this->getKategoriLabel($kategori) }}</h3>
                        @if ($addingKategori !== $kategori)
                            <button wire:click="openItemModal('{{ $kategori }}')" class="btn btn-primary btn-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4"></path>
                                </svg>
                                Tambah
                            </button>
                        @endif
                    </div>
                    <div class="p-4">
                        {{-- Inline Add Form --}}
                        @if ($addingKategori === $kategori)
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                                <div class="grid grid-cols-1 md:grid-cols-6 gap-3">
                                    <div class="md:col-span-2">
                                        <input type="text" wire:model="item_nama" class="input w-full text-sm"
                                            placeholder="Nama item *">
                                    </div>
                                    <div>
                                        <input type="number" wire:model="item_jumlah" class="input w-full text-sm"
                                            placeholder="Qty">
                                    </div>
                                    <div>
                                        <input type="number" wire:model="item_jumlah2" class="input w-full text-sm"
                                            placeholder="Qty2">
                                    </div>
                                    <div>
                                        <input type="number" wire:model="item_harga_satuan"
                                            class="input w-full text-sm" placeholder="Harga">
                                    </div>
                                    <div>
                                        <input type="number" wire:model="item_subtotal" class="input w-full text-sm"
                                            placeholder="Subtotal">
                                    </div>
                                </div>
                                <div class="flex gap-2 mt-3">
                                    <button wire:click="saveItem" class="btn btn-primary btn-sm">
                                        {{ $editingItemId ? 'Update' : 'Simpan' }}
                                    </button>
                                    <button wire:click="closeItemModal"
                                        class="btn btn-secondary btn-sm">Batal</button>
                                </div>
                            </div>
                        @endif

                        {{-- Items List --}}
                        @if (isset($items[$kategori]) && $items[$kategori]->count() > 0)
                            <div class="space-y-2">
                                @foreach ($items[$kategori] as $item)
                                    <div
                                        class="flex items-center justify-between py-2 px-3 bg-gray-50 rounded-lg hover:bg-gray-100">
                                        <div class="flex-1">
                                            <span class="font-medium">{{ $item->nama_item }}</span>
                                            <span class="text-gray-500 text-sm ml-2">
                                                @if ($item->jumlah)
                                                    ({{ $item->jumlah }}
                                                    @if ($item->jumlah2)
                                                        x {{ $item->jumlah2 }}
                                                    @endif
                                                    @if ($item->harga_satuan)
                                                        x {{ number_format($item->harga_satuan, 0) }}
                                                    @endif)
                                                @endif
                                            </span>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <span class="font-bold text-green-600">Rp
                                                {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                            <div class="flex gap-1">
                                                <button wire:click="editItem({{ $item->id }})"
                                                    class="text-blue-600 hover:text-blue-800 p-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                        </path>
                                                    </svg>
                                                </button>
                                                <button wire:click="deleteItem({{ $item->id }})"
                                                    wire:confirm="Hapus item ini?"
                                                    class="text-red-600 hover:text-red-800 p-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                        </path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            @if ($addingKategori !== $kategori)
                                <p class="text-gray-500 text-center py-4">Belum ada item</p>
                            @endif
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Summary -->
        <div class="card mt-6">
            <div class="p-4 bg-gray-50">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-600">Total Pemasukan: <span class="font-bold text-green-600">Rp
                                {{ number_format($totalPemasukan, 0, ',', '.') }}</span></p>
                        <p class="text-sm text-gray-600">Total Pengeluaran: <span class="font-bold text-red-600">Rp
                                {{ number_format($totalPengeluaran, 0, ',', '.') }}</span></p>
                        <p class="text-lg mt-2">Saldo: <span
                                class="font-bold {{ $saldo >= 0 ? 'text-green-600' : 'text-red-600' }}">Rp
                                {{ number_format($saldo, 0, ',', '.') }}</span></p>
                    </div>
                    <div class="flex gap-2">
                        <button wire:click="save" class="btn btn-primary">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            Simpan
                        </button>
                        <button wire:click="togglePreview" class="btn btn-secondary">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                </path>
                            </svg>
                            Preview & Cetak
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Print Styles -->
    <style>
        @page {
            size: 215mm 330mm;
            margin: 15mm;
        }

        @media print {
            body * {
                visibility: hidden;
            }

            #anggaran-print,
            #anggaran-print * {
                visibility: visible;
            }

            #anggaran-print {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                font-size: 12px !important;
                line-height: 1.5 !important;
            }

            #anggaran-print p,
            #anggaran-print td,
            #anggaran-print th {
                font-size: 12px !important;
                line-height: 1.5 !important;
                padding-top: 2px !important;
                padding-bottom: 2px !important;
                white-space: nowrap !important;
                overflow: visible !important;
            }

            #anggaran-print table {
                width: auto !important;
                table-layout: auto !important;
            }

            #anggaran-print td.text-right {
                min-width: 80px !important;
                padding-right: 8px !important;
            }

            #anggaran-print .text-sm {
                font-size: 11px !important;
            }

            #anggaran-print .text-base {
                font-size: 13px !important;
            }

            #anggaran-print .mb-4 {
                margin-bottom: 8px !important;
            }

            #anggaran-print .mb-6 {
                margin-bottom: 12px !important;
            }

            #anggaran-print .mt-8 {
                margin-top: 20px !important;
            }

            #anggaran-print .mt-12 {
                margin-top: 30px !important;
            }

            #anggaran-print .p-8 {
                padding: 8px !important;
            }

            .print\:hidden {
                display: none !important;
            }
        }
    </style>
</div>
