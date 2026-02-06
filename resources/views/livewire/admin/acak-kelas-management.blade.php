<div>
    <x-slot name="header">Acak Kelas</x-slot>

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
            <span class="font-medium text-gray-900">Acak Kelas</span>
        </nav>
    </div>

    <!-- Header Info -->
    <div class="mb-6 p-4 rounded-xl bg-gray-50 border border-gray-200">
        <h2 class="text-lg font-semibold text-gray-900">{{ $kegiatanUjian->nama_ujian }}</h2>
        <p class="text-sm text-gray-500 mt-1">{{ $kegiatanUjian->tahunAjaran->nama }} -
            {{ $kegiatanUjian->tahunAjaran->semester }}</p>
    </div>

    <!-- Flash Messages -->
    @if (session('message'))
        <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-800">
            {{ session('message') }}
        </div>
    @endif
    @if (session('error'))
        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800">
            {{ session('error') }}
        </div>
    @endif

    <!-- Pasangan Kelas Card -->
    <div class="card mb-6">
        <div class="p-6 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h3 class="text-lg font-semibold text-gray-900">Pasangan Kelas</h3>
                <button wire:click="openFormModal" class="btn btn-primary">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                        </path>
                    </svg>
                    Tambah Pasangan
                </button>
            </div>
        </div>

        <div class="p-6">
            @if ($pasangans->isEmpty())
                <div class="text-center py-8 text-gray-500">
                    <svg class="mx-auto h-12 w-12 text-gray-300 mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                    </svg>
                    <p>Belum ada pasangan kelas</p>
                    <p class="text-sm mt-1">Klik tombol "Tambah Pasangan" untuk memulai</p>
                </div>
            @else
                <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($pasangans as $pasangan)
                        <div class="rounded-xl border border-gray-200 bg-white p-4">
                            <div class="mb-4 flex items-center gap-3">
                                <span class="badge badge-primary">{{ $pasangan->kelas_a_nama }}</span>
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                </svg>
                                <span class="badge badge-secondary">{{ $pasangan->kelas_b_nama }}</span>
                            </div>

                            <div class="mb-4 text-sm">
                                @if ($pasangan->penempatans_count > 0)
                                    <span class="text-green-600">
                                        ✓ {{ $pasangan->penempatans_count }} siswa sudah ditempatkan
                                    </span>
                                @else
                                    <span class="text-amber-600">Belum di-generate</span>
                                @endif
                            </div>

                            <div class="flex gap-2">
                                @if ($pasangan->penempatans_count > 0)
                                    <a href="{{ route('admin.kegiatan-ujian.acak-kelas.hasil', ['kegiatanUjianId' => $kegiatanUjian->id, 'pasanganId' => $pasangan->id]) }}"
                                        class="btn btn-secondary btn-sm flex-1" wire:navigate>
                                        Lihat Hasil
                                    </a>
                                    <button wire:click="confirmReset({{ $pasangan->id }})"
                                        class="btn btn-ghost btn-sm text-amber-600">
                                        Reset
                                    </button>
                                @else
                                    <button wire:click="confirmGenerate({{ $pasangan->id }})"
                                        class="btn btn-primary btn-sm flex-1">
                                        Generate
                                    </button>
                                @endif
                                <button wire:click="deletePasangan({{ $pasangan->id }})"
                                    wire:confirm="Yakin ingin menghapus pasangan ini?"
                                    class="btn btn-ghost btn-sm text-red-600">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Ruang Ujian Card -->
    <div class="card">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Ruang Ujian Tersedia</h3>
        </div>

        @if ($ruangWithOccupancy->isEmpty())
            <div class="p-6">
                <div class="rounded-xl bg-amber-50 border border-amber-200 p-4 text-amber-800">
                    Belum ada ruang ujian. Silakan tambahkan di menu
                    <a href="{{ route('admin.ruang-ujian') }}" class="font-semibold underline" wire:navigate>Ruang
                        Ujian</a>.
                </div>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama Ruang</th>
                            <th>Kapasitas</th>
                            <th>Status Terisi</th>
                            <th>Kelas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ruangWithOccupancy as $ruang)
                            <tr>
                                <td class="font-mono">{{ $ruang['kode'] }}</td>
                                <td>{{ $ruang['nama'] }}</td>
                                <td><span class="badge badge-secondary">{{ $ruang['kapasitas'] }} siswa</span></td>
                                <td>
                                    @if ($ruang['terisi'] > 0)
                                        <div class="flex items-center gap-2">
                                            <div class="w-24 bg-gray-200 rounded-full h-2">
                                                <div class="bg-blue-600 h-2 rounded-full"
                                                    style="width: {{ min(100, ($ruang['terisi'] / $ruang['kapasitas']) * 100) }}%">
                                                </div>
                                            </div>
                                            <span
                                                class="text-sm font-medium {{ $ruang['terisi'] >= $ruang['kapasitas'] ? 'text-red-600' : 'text-gray-600' }}">
                                                {{ $ruang['terisi'] }}/{{ $ruang['kapasitas'] }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-sm">Kosong</span>
                                    @endif
                                </td>
                                <td>
                                    @if (count($ruang['kelas_list']) > 0)
                                        <div class="flex flex-wrap gap-1">
                                            @foreach ($ruang['kelas_list'] as $kelasNama)
                                                <span class="badge badge-primary">{{ $kelasNama }}</span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-sm">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-gray-50">
                            <td colspan="2" class="font-semibold">Total</td>
                            <td><span class="badge badge-primary">{{ $ruangWithOccupancy->sum('kapasitas') }}
                                    siswa</span></td>
                            <td><span class="badge badge-secondary">{{ $ruangWithOccupancy->sum('terisi') }}
                                    terisi</span></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif
    </div>

    <!-- Form Modal -->
    @if ($showFormModal)
        <template x-teleport="#modal-portal">
            <div class="fixed inset-0 z-[9999] overflow-y-auto">
                <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" wire:click="closeFormModal"></div>
                <div class="fixed inset-0 flex items-center justify-center p-4">
                    <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl">
                        <form wire:submit="savePasangan">
                            <div class="border-b border-gray-200 px-6 py-4">
                                <h3 class="text-lg font-semibold text-gray-900">Tambah Pasangan Kelas</h3>
                            </div>
                            <div class="px-6 py-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium mb-1">Kelas A</label>
                                    <select wire:model="kelas_a" class="input w-full">
                                        <option value="">Pilih Kelas A</option>
                                        @foreach ($kelasList as $kelas)
                                            <option value="{{ $kelas->nama }}">{{ $kelas->tingkat }} -
                                                {{ $kelas->nama }}</option>
                                        @endforeach
                                    </select>
                                    @error('kelas_a')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Kelas B</label>
                                    <select wire:model="kelas_b" class="input w-full">
                                        <option value="">Pilih Kelas B</option>
                                        @foreach ($kelasList as $kelas)
                                            <option value="{{ $kelas->nama }}">{{ $kelas->tingkat }} -
                                                {{ $kelas->nama }}</option>
                                        @endforeach
                                    </select>
                                    @error('kelas_b')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div
                                class="border-t border-gray-200 bg-gray-50 px-6 py-4 flex justify-end gap-3 rounded-b-2xl">
                                <button type="button" wire:click="closeFormModal"
                                    class="btn btn-secondary">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </template>
    @endif

    <!-- Confirm Generate Modal -->
    @if ($showConfirmGenerateModal)
        <template x-teleport="#modal-portal">
            <div class="fixed inset-0 z-[9999] overflow-y-auto">
                <div class="fixed inset-0 bg-black/60 backdrop-blur-sm"
                    wire:click="$set('showConfirmGenerateModal', false)"></div>
                <div class="fixed inset-0 flex items-center justify-center p-4">
                    <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl p-6">
                        <h3 class="text-lg font-semibold mb-2">Generate Penempatan?</h3>
                        <p class="text-gray-500 mb-4">Siswa dari kedua kelas akan diacak dan ditempatkan ke ruang ujian
                            berdasarkan kapasitas.</p>
                        <div class="flex justify-end gap-3">
                            <button wire:click="$set('showConfirmGenerateModal', false)"
                                class="btn btn-secondary">Batal</button>
                            <button wire:click="generatePenempatan" class="btn btn-primary">Generate</button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    @endif

    <!-- Confirm Reset Modal -->
    @if ($showConfirmResetModal)
        <template x-teleport="#modal-portal">
            <div class="fixed inset-0 z-[9999] overflow-y-auto">
                <div class="fixed inset-0 bg-black/60 backdrop-blur-sm"
                    wire:click="$set('showConfirmResetModal', false)"></div>
                <div class="fixed inset-0 flex items-center justify-center p-4">
                    <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl p-6">
                        <h3 class="text-lg font-semibold mb-2">Reset Penempatan?</h3>
                        <p class="text-gray-500 mb-4">Semua data penempatan siswa untuk pasangan ini akan dihapus.</p>
                        <div class="flex justify-end gap-3">
                            <button wire:click="$set('showConfirmResetModal', false)"
                                class="btn btn-secondary">Batal</button>
                            <button wire:click="resetPenempatan" class="btn btn-destructive">Reset</button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    @endif

    <!-- Result Modal -->
    @if ($showResultModal)
        <template x-teleport="#modal-portal">
            <div class="fixed inset-0 z-[9999] overflow-y-auto">
                <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" wire:click="closeResultModal"></div>
                <div class="fixed inset-0 flex items-center justify-center p-4">
                    <div class="relative w-full max-w-4xl max-h-[80vh] bg-white rounded-2xl shadow-2xl flex flex-col">
                        <div class="border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">Hasil Penempatan Siswa</h3>
                            <button wire:click="closeResultModal" class="btn btn-ghost btn-sm">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <div class="p-6 overflow-y-auto flex-1">
                            @foreach ($penempatanByRuang as $ruangId => $penempatans)
                                @php
                                    $ruang = $penempatans->first()->ruangUjian;
                                @endphp
                                <div class="mb-6 last:mb-0">
                                    <div class="mb-3 flex items-center justify-between">
                                        <h4 class="font-semibold text-gray-900">
                                            {{ $ruang->kode }} - {{ $ruang->nama }}
                                        </h4>
                                        <span class="badge badge-secondary">
                                            {{ $penempatans->count() }} / {{ $ruang->kapasitas }} siswa
                                        </span>
                                    </div>

                                    <div class="overflow-x-auto rounded-xl border border-gray-200">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>No. Peserta</th>
                                                    <th>Nama</th>
                                                    <th>Asal Kelas</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($penempatans as $penempatan)
                                                    <tr>
                                                        <td>{{ $penempatan->nomor_urut }}</td>
                                                        <td class="font-mono">{{ $penempatan->nomor_peserta ?? '-' }}
                                                        </td>
                                                        <td>{{ $penempatan->siswa->nama_lengkap }}</td>
                                                        <td>
                                                            <span
                                                                class="badge badge-{{ $loop->index % 2 == 0 ? 'primary' : 'secondary' }}">
                                                                {{ $penempatan->asal_kelas }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </template>
    @endif
</div>
