<div>
    <x-slot name="header">Penempatan Per Kelas</x-slot>

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
            <span class="font-medium text-gray-900">Penempatan Per Kelas</span>
        </nav>
    </div>

    <!-- Header Info -->
    <div class="mb-6 p-4 rounded-xl bg-gray-50 border border-gray-200">
        <h2 class="text-lg font-semibold text-gray-900">{{ $kegiatanUjian->nama_ujian }}</h2>
        <p class="text-sm text-gray-500 mt-1">{{ $kegiatanUjian->tahunAjaran->nama }} -
            {{ $kegiatanUjian->tahunAjaran->semester }}</p>
        <p class="text-sm text-blue-600 mt-2">
            <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Mode: Penempatan berurutan berdasarkan nama (A-Z), tidak diacak
        </p>
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

    <!-- Kelas List Card -->
    <div class="card mb-6">
        <div class="p-6 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h3 class="text-lg font-semibold text-gray-900">Daftar Kelas</h3>
                <button wire:click="openFormModal" class="btn btn-primary">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                        </path>
                    </svg>
                    Generate Penempatan
                </button>
            </div>
        </div>

        <div class="p-6">
            @if ($kelasWithStatus->isEmpty())
                <div class="text-center py-8 text-gray-500">
                    <svg class="mx-auto h-12 w-12 text-gray-300 mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                        </path>
                    </svg>
                    <p>Belum ada data kelas</p>
                    <p class="text-sm mt-1">Silakan tambahkan kelas terlebih dahulu</p>
                </div>
            @else
                <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($kelasWithStatus as $kelas)
                        <div class="rounded-xl border border-gray-200 bg-white p-4">
                            <div class="mb-4 flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                                    <span class="text-blue-600 font-semibold text-sm">{{ $kelas['tingkat'] }}</span>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ $kelas['nama'] }}</h4>
                                    <p class="text-sm text-gray-500">Tingkat {{ $kelas['tingkat'] }}</p>
                                </div>
                            </div>

                            <div class="mb-4 text-sm">
                                @if ($kelas['penempatans_count'] > 0)
                                    <span class="text-green-600">
                                        ✓ {{ $kelas['penempatans_count'] }} siswa sudah ditempatkan
                                    </span>
                                @else
                                    <span class="text-amber-600">Belum ada penempatan</span>
                                @endif
                            </div>

                            <div class="flex gap-2">
                                @if ($kelas['penempatans_count'] > 0)
                                    <a href="{{ route('admin.kegiatan-ujian.penempatan-per-kelas.hasil', ['kegiatanUjianId' => $kegiatanUjian->id, 'kelasNama' => $kelas['nama']]) }}"
                                        class="btn btn-secondary btn-sm flex-1" wire:navigate>
                                        Lihat Hasil
                                    </a>
                                    <button wire:click="confirmReset('{{ $kelas['nama'] }}')"
                                        class="btn btn-ghost btn-sm text-amber-600">
                                        Reset
                                    </button>
                                @else
                                    <span class="text-gray-400 text-sm flex-1 text-center py-2">
                                        Gunakan tombol "Generate Penempatan" di atas
                                    </span>
                                @endif
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
                        <form wire:submit="confirmGenerate">
                            <div class="border-b border-gray-200 px-6 py-4">
                                <h3 class="text-lg font-semibold text-gray-900">Generate Penempatan Kelas</h3>
                            </div>
                            <div class="px-6 py-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium mb-1">Pilih Kelas</label>
                                    <select wire:model="selectedKelas" class="input w-full">
                                        <option value="">Pilih Kelas</option>
                                        @foreach ($kelasWithStatus as $kelas)
                                            <option value="{{ $kelas['nama'] }}">{{ $kelas['tingkat'] }} -
                                                {{ $kelas['nama'] }}
                                                @if ($kelas['penempatans_count'] > 0)
                                                    ({{ $kelas['penempatans_count'] }} sudah ditempatkan)
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('selectedKelas')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="rounded-lg bg-blue-50 border border-blue-200 p-3 text-sm text-blue-800">
                                    <strong>Catatan:</strong> Siswa akan ditempatkan berurutan berdasarkan nama (A-Z),
                                    tidak diacak.
                                    Jika kelas sudah memiliki penempatan, data lama akan ditimpa.
                                </div>
                            </div>
                            <div
                                class="border-t border-gray-200 bg-gray-50 px-6 py-4 flex justify-end gap-3 rounded-b-2xl">
                                <button type="button" wire:click="closeFormModal"
                                    class="btn btn-secondary">Batal</button>
                                <button type="submit" class="btn btn-primary">Lanjutkan</button>
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
                        <p class="text-gray-500 mb-4">
                            Siswa dari kelas <strong>{{ $actionKelasNama }}</strong> akan ditempatkan ke ruang ujian
                            berurutan berdasarkan nama (A-Z).
                        </p>
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
                        <p class="text-gray-500 mb-4">
                            Semua data penempatan siswa kelas <strong>{{ $actionKelasNama }}</strong> akan dihapus.
                        </p>
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
</div>
