<div>
    <x-slot name="header">Dashboard</x-slot>

    <div class="animate-fade-up">
        <!-- Welcome Card -->
        <div class="card p-8 mb-8">
            <h2 class="text-2xl font-bold text-[hsl(var(--foreground))] mb-2">
                Selamat datang, {{ auth()->user()->name }}! 👋
            </h2>
            <p class="text-[hsl(var(--muted-foreground))]">
                @if ($tahunAjaranAktif)
                    Tahun Ajaran Aktif: <span class="font-semibold">{{ $tahunAjaranAktif->nama }}</span>
                    (Semester {{ $tahunAjaranAktif->semester }})
                @else
                    <span class="text-yellow-600">Belum ada tahun ajaran aktif. Silahkan atur di menu Master Data.</span>
                @endif
            </p>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Siswa -->
            <a href="{{ route('admin.siswa') }}" class="card p-6 hover:shadow-lg transition-shadow" wire:navigate>
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z">
                            </path>
                        </svg>
                    </div>
                    @if ($stats['siswa'] > 0)
                        <span class="badge badge-success">{{ $stats['siswa_aktif'] }} aktif</span>
                    @endif
                </div>
                <h3 class="text-3xl font-bold text-[hsl(var(--foreground))]">{{ number_format($stats['siswa']) }}</h3>
                <p class="text-sm text-[hsl(var(--muted-foreground))]">Total Siswa</p>
            </a>

            <!-- Guru -->
            <a href="{{ route('admin.guru') }}" class="card p-6 hover:shadow-lg transition-shadow" wire:navigate>
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg>
                    </div>
                    @if ($stats['guru'] > 0)
                        <span class="badge badge-success">{{ $stats['guru_aktif'] }} aktif</span>
                    @endif
                </div>
                <h3 class="text-3xl font-bold text-[hsl(var(--foreground))]">{{ number_format($stats['guru']) }}</h3>
                <p class="text-sm text-[hsl(var(--muted-foreground))]">Total Guru</p>
            </a>

            <!-- Pelajaran -->
            <a href="{{ route('admin.pelajaran') }}" class="card p-6 hover:shadow-lg transition-shadow" wire:navigate>
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-lg bg-purple-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                            </path>
                        </svg>
                    </div>
                    @if ($stats['pelajaran'] > 0)
                        <span class="badge badge-success">{{ $stats['pelajaran_aktif'] }} aktif</span>
                    @endif
                </div>
                <h3 class="text-3xl font-bold text-[hsl(var(--foreground))]">{{ number_format($stats['pelajaran']) }}
                </h3>
                <p class="text-sm text-[hsl(var(--muted-foreground))]">Total Mapel</p>
            </a>

            <!-- Kelas -->
            <a href="{{ route('admin.kelas') }}" class="card p-6 hover:shadow-lg transition-shadow" wire:navigate>
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-lg bg-yellow-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg>
                    </div>
                </div>
                <h3 class="text-3xl font-bold text-[hsl(var(--foreground))]">{{ number_format($stats['kelas']) }}</h3>
                <p class="text-sm text-[hsl(var(--muted-foreground))]">Total Kelas</p>
            </a>
        </div>

        <!-- Second Stats Row -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Ruang Ujian -->
            <a href="{{ route('admin.ruang-ujian') }}" class="card p-6 hover:shadow-lg transition-shadow"
                wire:navigate>
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-lg bg-indigo-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>
                        </svg>
                    </div>
                </div>
                <h3 class="text-3xl font-bold text-[hsl(var(--foreground))]">{{ number_format($stats['ruang_ujian']) }}
                </h3>
                <p class="text-sm text-[hsl(var(--muted-foreground))]">Total Ruang Ujian</p>
            </a>

            <!-- Kegiatan Ujian -->
            <a href="{{ route('admin.kegiatan-ujian') }}" class="card p-6 hover:shadow-lg transition-shadow"
                wire:navigate>
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-lg bg-red-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                            </path>
                        </svg>
                    </div>
                </div>
                <h3 class="text-3xl font-bold text-[hsl(var(--foreground))]">
                    {{ number_format($stats['kegiatan_ujian']) }}</h3>
                <p class="text-sm text-[hsl(var(--muted-foreground))]">Total Kegiatan Ujian</p>
            </a>

            <!-- Users -->
            <a href="{{ route('admin.users') }}" class="card p-6 hover:shadow-lg transition-shadow" wire:navigate>
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                    </div>
                </div>
                <h3 class="text-3xl font-bold text-[hsl(var(--foreground))]">{{ number_format($stats['users']) }}</h3>
                <p class="text-sm text-[hsl(var(--muted-foreground))]">Total Users</p>
            </a>
        </div>

        <!-- Quick Actions & Recent Kegiatan -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Quick Actions -->
            <div class="card p-6">
                <h3 class="text-lg font-semibold text-[hsl(var(--foreground))] mb-4">Aksi Cepat</h3>
                <div class="grid grid-cols-2 gap-4">
                    <a href="{{ route('admin.kegiatan-ujian') }}"
                        class="flex flex-col items-center justify-center p-4 rounded-lg bg-[hsl(var(--secondary))] hover:bg-[hsl(var(--accent))] transition-colors"
                        wire:navigate>
                        <svg class="w-8 h-8 text-[hsl(var(--foreground))] mb-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                            </path>
                        </svg>
                        <span class="text-sm font-medium text-[hsl(var(--foreground))]">Buat Kegiatan Ujian</span>
                    </a>
                    <a href="{{ route('admin.school-profile') }}"
                        class="flex flex-col items-center justify-center p-4 rounded-lg bg-[hsl(var(--secondary))] hover:bg-[hsl(var(--accent))] transition-colors"
                        wire:navigate>
                        <svg class="w-8 h-8 text-[hsl(var(--foreground))] mb-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg>
                        <span class="text-sm font-medium text-[hsl(var(--foreground))]">Profil Sekolah</span>
                    </a>
                    <a href="{{ route('admin.siswa') }}"
                        class="flex flex-col items-center justify-center p-4 rounded-lg bg-[hsl(var(--secondary))] hover:bg-[hsl(var(--accent))] transition-colors"
                        wire:navigate>
                        <svg class="w-8 h-8 text-[hsl(var(--foreground))] mb-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                            </path>
                        </svg>
                        <span class="text-sm font-medium text-[hsl(var(--foreground))]">Sync Data Siswa</span>
                    </a>
                    <a href="{{ route('admin.guru') }}"
                        class="flex flex-col items-center justify-center p-4 rounded-lg bg-[hsl(var(--secondary))] hover:bg-[hsl(var(--accent))] transition-colors"
                        wire:navigate>
                        <svg class="w-8 h-8 text-[hsl(var(--foreground))] mb-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                            </path>
                        </svg>
                        <span class="text-sm font-medium text-[hsl(var(--foreground))]">Sync Data Guru</span>
                    </a>
                </div>
            </div>

            <!-- Recent Kegiatan Ujian -->
            <div class="card p-6">
                <h3 class="text-lg font-semibold text-[hsl(var(--foreground))] mb-4">Kegiatan Ujian Terbaru</h3>
                @if ($recentKegiatanUjian->count() > 0)
                    <div class="space-y-3">
                        @foreach ($recentKegiatanUjian as $kegiatan)
                            <a href="{{ route('admin.kegiatan-ujian.perangkat', $kegiatan->id) }}"
                                class="flex items-center justify-between p-3 rounded-lg bg-[hsl(var(--secondary))] hover:bg-[hsl(var(--accent))] transition-colors"
                                wire:navigate>
                                <div>
                                    <p class="font-medium text-[hsl(var(--foreground))]">{{ $kegiatan->nama }}</p>
                                    <p class="text-xs text-[hsl(var(--muted-foreground))]">
                                        {{ $kegiatan->created_at->diffForHumans() }}</p>
                                </div>
                                <svg class="w-5 h-5 text-[hsl(var(--muted-foreground))]" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-[hsl(var(--muted-foreground))]">
                        <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                        <p>Belum ada kegiatan ujian</p>
                        <a href="{{ route('admin.kegiatan-ujian') }}" class="btn btn-primary btn-sm mt-3"
                            wire:navigate>
                            Buat Kegiatan Ujian
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
