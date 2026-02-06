<x-layouts.app title="Perangkat Panitia PAS/PTS">
    <!-- Navigation -->
    <nav class="nav-apple" x-data="{ scrolled: false, mobileOpen: false }" @scroll.window="scrolled = window.scrollY > 50"
        :class="{ 'scrolled': scrolled }">
        <div class="container-tight">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <a href="/" class="flex items-center gap-2 font-semibold text-[hsl(var(--foreground))]">
                    <div class="w-8 h-8 rounded-lg bg-[hsl(var(--primary))] flex items-center justify-center">
                        <svg class="w-5 h-5 text-[hsl(var(--primary-foreground))]" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                    </div>
                    <span class="hidden sm:inline">Perangkat Panitia</span>
                </a>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center gap-8">
                    <a href="#features"
                        class="text-sm font-medium text-[hsl(var(--muted-foreground))] hover:text-[hsl(var(--foreground))] transition-colors">
                        Fitur
                    </a>
                    <a href="#workflow"
                        class="text-sm font-medium text-[hsl(var(--muted-foreground))] hover:text-[hsl(var(--foreground))] transition-colors">
                        Alur Kerja
                    </a>
                </div>

                <!-- Right Side -->
                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn btn-primary btn-sm" wire:navigate>
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary btn-sm" wire:navigate>
                            Masuk
                        </a>
                    @endauth

                    <!-- Mobile Menu Button -->
                    <button @click="mobileOpen = !mobileOpen"
                        class="md:hidden p-2 rounded-lg hover:bg-[hsl(var(--accent))]">
                        <svg x-show="!mobileOpen" class="w-6 h-6" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                        <svg x-show="mobileOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div x-show="mobileOpen" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-4"
                x-cloak class="md:hidden py-4 border-t border-[hsl(var(--border))]">
                <div class="flex flex-col gap-4">
                    <a href="#features"
                        class="text-sm font-medium text-[hsl(var(--muted-foreground))] hover:text-[hsl(var(--foreground))]">Fitur</a>
                    <a href="#workflow"
                        class="text-sm font-medium text-[hsl(var(--muted-foreground))] hover:text-[hsl(var(--foreground))]">Alur
                        Kerja</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero gradient-mesh">
        <div class="hero-content animate-fade-up">
            <div
                class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-[hsl(var(--secondary))] text-sm font-medium text-[hsl(var(--secondary-foreground))] mb-6">
                <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                </svg>
                Sistem Informasi Panitia Ujian
            </div>

            <h1 class="hero-title text-balance mb-6">
                Perangkat Panitia<br>PAS & PTS
            </h1>

            <p class="text-lg md:text-xl text-[hsl(var(--muted-foreground))] max-w-2xl mx-auto mb-8">
                Aplikasi lengkap untuk membantu panitia ujian dalam mengelola data,
                membuat dokumen POS, dan menyiapkan perangkat ujian dengan mudah.
            </p>

            <div class="flex flex-wrap items-center justify-center gap-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg" wire:navigate>
                        Masuk Dashboard
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg" wire:navigate>
                        Mulai Sekarang
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </a>
                @endauth
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="section-padding">
        <div class="container-tight">
            <div class="text-center mb-16 animate-fade-up">
                <h2 class="text-3xl md:text-4xl font-bold mb-4">Fitur Unggulan</h2>
                <p class="text-[hsl(var(--muted-foreground))] max-w-2xl mx-auto">
                    Semua yang dibutuhkan panitia ujian dalam satu aplikasi terpadu.
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Feature 1 -->
                <div class="card p-6 animate-fade-up delay-100">
                    <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Data Master Lengkap</h3>
                    <p class="text-[hsl(var(--muted-foreground))] text-sm">
                        Kelola data siswa, guru, mata pelajaran, kelas, dan ruang ujian dengan mudah. Support sync dari
                        API.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="card p-6 animate-fade-up delay-200">
                    <div class="w-12 h-12 rounded-lg bg-purple-100 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Dokumen POS Otomatis</h3>
                    <p class="text-[hsl(var(--muted-foreground))] text-sm">
                        Buat Prosedur Operasional Standar (POS) ujian sesuai regulasi Kemdikdasmen. Export ke Word dan
                        PDF.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="card p-6 animate-fade-up delay-300">
                    <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Kegiatan Ujian</h3>
                    <p class="text-[hsl(var(--muted-foreground))] text-sm">
                        Buat dan kelola kegiatan PAS/PTS dengan mudah. Lengkap dengan perangkat ujian terkait.
                    </p>
                </div>

                <!-- Feature 4 -->
                <div class="card p-6 animate-fade-up delay-100">
                    <div class="w-12 h-12 rounded-lg bg-yellow-100 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Sinkronisasi API</h3>
                    <p class="text-[hsl(var(--muted-foreground))] text-sm">
                        Sync data siswa, guru, dan mapel langsung dari aplikasi lain via API. Hemat waktu input data.
                    </p>
                </div>

                <!-- Feature 5 -->
                <div class="card p-6 animate-fade-up delay-200">
                    <div class="w-12 h-12 rounded-lg bg-red-100 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Manajemen Ruang</h3>
                    <p class="text-[hsl(var(--muted-foreground))] text-sm">
                        Kelola ruang ujian dengan kapasitas dan lokasi. Siapkan denah penempatan peserta.
                    </p>
                </div>

                <!-- Feature 6 -->
                <div class="card p-6 animate-fade-up delay-300">
                    <div class="w-12 h-12 rounded-lg bg-indigo-100 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Profil Sekolah</h3>
                    <p class="text-[hsl(var(--muted-foreground))] text-sm">
                        Atur informasi sekolah, kop surat, dan data kepala sekolah untuk dokumen resmi.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Workflow Section -->
    <section id="workflow" class="section-padding bg-[hsl(var(--secondary))]">
        <div class="container-tight">
            <div class="text-center mb-16 animate-fade-up">
                <h2 class="text-3xl md:text-4xl font-bold mb-4">Alur Kerja Mudah</h2>
                <p class="text-[hsl(var(--muted-foreground))] max-w-2xl mx-auto">
                    Langkah-langkah sederhana untuk menyiapkan perangkat ujian.
                </p>
            </div>

            <div class="grid md:grid-cols-4 gap-6">
                <div class="card-premium p-6 text-center relative">
                    <div
                        class="w-12 h-12 rounded-full bg-[hsl(var(--primary))] text-[hsl(var(--primary-foreground))] flex items-center justify-center mx-auto mb-4 text-xl font-bold">
                        1
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Siapkan Data</h3>
                    <p class="text-sm text-[hsl(var(--muted-foreground))]">
                        Input atau sync data siswa, guru, mapel, dan ruang ujian
                    </p>
                </div>
                <div class="card-premium p-6 text-center">
                    <div
                        class="w-12 h-12 rounded-full bg-[hsl(var(--primary))] text-[hsl(var(--primary-foreground))] flex items-center justify-center mx-auto mb-4 text-xl font-bold">
                        2
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Buat Kegiatan</h3>
                    <p class="text-sm text-[hsl(var(--muted-foreground))]">
                        Buat kegiatan ujian PAS atau PTS baru
                    </p>
                </div>
                <div class="card-premium p-6 text-center">
                    <div
                        class="w-12 h-12 rounded-full bg-[hsl(var(--primary))] text-[hsl(var(--primary-foreground))] flex items-center justify-center mx-auto mb-4 text-xl font-bold">
                        3
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Buat Dokumen</h3>
                    <p class="text-sm text-[hsl(var(--muted-foreground))]">
                        Generate POS dan perangkat ujian lainnya
                    </p>
                </div>
                <div class="card-premium p-6 text-center">
                    <div
                        class="w-12 h-12 rounded-full bg-[hsl(var(--primary))] text-[hsl(var(--primary-foreground))] flex items-center justify-center mx-auto mb-4 text-xl font-bold">
                        4
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Export & Cetak</h3>
                    <p class="text-sm text-[hsl(var(--muted-foreground))]">
                        Download dokumen dalam format Word atau PDF
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="section-padding gradient-hero">
        <div class="container-tight text-center">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">Siap Mempersiapkan Ujian?</h2>
            <p class="text-[hsl(var(--muted-foreground))] max-w-xl mx-auto mb-8">
                Kelola kegiatan PAS/PTS dengan lebih efisien. Mulai sekarang dan rasakan kemudahannya.
            </p>
            <div class="flex flex-wrap items-center justify-center gap-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg" wire:navigate>
                        Masuk Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg" wire:navigate>
                        Mulai Sekarang
                    </a>
                @endauth
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="border-t border-[hsl(var(--border))] py-12">
        <div class="container-tight">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="text-sm text-[hsl(var(--muted-foreground))]">
                    © {{ date('Y') }} Perangkat Panitia PAS/PTS. Dikembangkan untuk kemudahan panitia ujian.
                </p>
                <div class="flex items-center gap-6">
                    <span class="text-sm text-[hsl(var(--muted-foreground))]">
                        Developed by Fahmie Al Khudhorie
                    </span>
                </div>
            </div>
        </div>
    </footer>
</x-layouts.app>
