<div>
    <x-slot name="header">Kartu Peserta Ujian</x-slot>

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
            <span class="font-medium text-gray-900">Kartu Peserta</span>
        </nav>
    </div>

    <!-- Header Info -->
    <div class="mb-6 p-4 rounded-xl bg-gray-50 border border-gray-200">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">{{ $kegiatanUjian->nama_ujian }}</h2>
                <p class="text-sm text-gray-500 mt-1">{{ $kegiatanUjian->tahunAjaran->nama }} -
                    {{ $kegiatanUjian->tahunAjaran->semester }}</p>
            </div>
            <div class="flex items-center gap-3">
                <button wire:click="generateNomorPeserta"
                    wire:confirm="Generate nomor peserta untuk semua siswa yang sudah ditempatkan?"
                    class="btn btn-primary btn-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                    </svg>
                    Generate Nomor
                </button>
                <span class="badge badge-secondary">{{ $siswas->count() }} siswa</span>
                @if (count($selectedSiswa) > 0)
                    <span class="badge badge-primary">{{ count($selectedSiswa) }} dipilih</span>
                @endif
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filters Card -->
    <div class="card mb-6">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Filter & Pencarian</h3>
        </div>
        <div class="p-6">
            <div class="flex flex-col lg:flex-row lg:items-end gap-4">
                <!-- Search Input -->
                <div class="flex-1">
                    <label class="block text-sm font-medium mb-1">Cari Siswa</label>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Ketik nama atau NISN..."
                        class="input w-full">
                </div>

                <!-- Filter Tingkat -->
                <div class="w-full lg:w-44">
                    <label class="block text-sm font-medium mb-1">Tingkat</label>
                    <select wire:model.live="selectedTingkat" class="input w-full">
                        <option value="">Semua Tingkat</option>
                        <option value="MI">MI</option>
                        <option value="SMP">SMP</option>
                    </select>
                </div>

                <!-- Filter Kelas -->
                <div class="w-full lg:w-52">
                    <label class="block text-sm font-medium mb-1">Kelas</label>
                    <select wire:model.live="selectedKelas" class="input w-full">
                        <option value="">Semua Kelas</option>
                        @foreach ($kelasList as $kelas)
                            <option value="{{ $kelas->nama }}">{{ $kelas->tingkat }} - {{ $kelas->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Print Button -->
                <div class="flex-shrink-0">
                    @if (count($selectedSiswa) > 0)
                        <a href="{{ route('admin.kegiatan-ujian.kartu-peserta.print', ['kegiatanUjianId' => $kegiatanUjian->id, 'siswa_ids' => implode(',', $selectedSiswa)]) }}"
                            target="_blank" class="btn btn-primary">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                </path>
                            </svg>
                            Cetak ({{ count($selectedSiswa) }})
                        </a>
                    @else
                        <button type="button" class="btn btn-secondary" disabled>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                </path>
                            </svg>
                            Pilih Siswa
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Student Table -->
    <div class="card">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Daftar Siswa</h3>
                @if (count($selectedSiswa) > 0)
                    <span class="badge badge-primary">{{ count($selectedSiswa) }} siswa dipilih</span>
                @endif
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th class="w-14">
                            <input type="checkbox" wire:model.live="selectAll"
                                class="rounded border-gray-300 text-gray-800 focus:ring-gray-500">
                        </th>
                        <th class="w-16">No</th>
                        <th>NISN</th>
                        <th>Nama Lengkap</th>
                        <th>Kelas</th>
                        <th>Jenis Kelamin</th>
                        <th class="text-center w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($siswas as $index => $siswa)
                        <tr wire:key="siswa-{{ $siswa->id }}"
                            class="{{ in_array((string) $siswa->id, $selectedSiswa) ? 'bg-gray-50' : '' }}">
                            <td>
                                <input type="checkbox" value="{{ $siswa->id }}"
                                    wire:click="toggleSiswa({{ $siswa->id }})" @checked(in_array((string) $siswa->id, $selectedSiswa))
                                    class="rounded border-gray-300 text-gray-800 focus:ring-gray-500">
                            </td>
                            <td>{{ $index + 1 }}</td>
                            <td class="font-mono">{{ $siswa->nisn ?? '-' }}</td>
                            <td class="font-medium">{{ $siswa->nama_lengkap }}</td>
                            <td>{{ $siswa->tingkat_rombel ?? '-' }}</td>
                            <td>
                                @if ($siswa->jenis_kelamin === 'L')
                                    <span class="badge badge-primary">Laki-laki</span>
                                @else
                                    <span class="badge badge-secondary">Perempuan</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <button wire:click="previewKartu({{ $siswa->id }})" class="btn btn-ghost btn-sm">
                                    Preview
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-12 text-gray-500">
                                @if ($search || $selectedTingkat || $selectedKelas)
                                    Tidak ada siswa yang sesuai filter
                                @else
                                    Belum ada data siswa
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Preview Modal -->
    @if ($showPreviewModal && $previewSiswa)
        <template x-teleport="#modal-portal">
            <div class="fixed inset-0 z-[9999] overflow-y-auto">
                <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" wire:click="closePreview"></div>
                <div class="fixed inset-0 flex items-center justify-center p-4">
                    <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl">
                        <div class="border-b border-gray-200 px-6 py-4">
                            <h3 class="text-lg font-semibold text-gray-900">Preview Kartu Peserta</h3>
                        </div>
                        <div class="p-6">
                            <!-- Card Preview -->
                            <div class="border-2 border-gray-200 rounded-xl overflow-hidden">
                                <div class="bg-gray-800 text-white text-center py-3">
                                    <h4 class="font-bold uppercase">Kartu Peserta Ujian</h4>
                                    <p class="text-sm text-gray-300">{{ $kegiatanUjian->nama_ujian }}</p>
                                </div>
                                <div class="p-4 space-y-2 text-sm">
                                    <div class="flex">
                                        <span class="w-20 text-gray-500">NISN</span>
                                        <span class="font-medium">: {{ $previewSiswa->nisn ?? '-' }}</span>
                                    </div>
                                    <div class="flex">
                                        <span class="w-20 text-gray-500">Nama</span>
                                        <span class="font-medium">: {{ $previewSiswa->nama_lengkap }}</span>
                                    </div>
                                    <div class="flex">
                                        <span class="w-20 text-gray-500">Kelas</span>
                                        <span class="font-medium">: {{ $previewSiswa->tingkat_rombel ?? '-' }}</span>
                                    </div>
                                    <div class="flex">
                                        <span class="w-20 text-gray-500">L/P</span>
                                        <span class="font-medium">:
                                            {{ $previewSiswa->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="mt-6 flex gap-3">
                                <button type="button" wire:click="closePreview"
                                    class="btn btn-secondary flex-1">Tutup</button>
                                <a href="{{ route('admin.kegiatan-ujian.kartu-peserta.print', ['kegiatanUjianId' => $kegiatanUjian->id, 'siswa_ids' => $previewSiswa->id]) }}"
                                    target="_blank" class="btn btn-primary flex-1">
                                    Cetak Kartu
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    @endif
</div>
