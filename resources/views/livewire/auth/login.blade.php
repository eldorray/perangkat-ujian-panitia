<div class="animate-fade-up">
    <div class="mb-8 text-center">
        <div class="w-16 h-16 rounded-2xl bg-[hsl(var(--primary))] flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-[hsl(var(--primary-foreground))]" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-[hsl(var(--foreground))]">Perangkat Panitia PAS/PTS</h2>
        <p class="text-[hsl(var(--muted-foreground))] mt-1">Masuk untuk melanjutkan ke dashboard</p>
    </div>

    @if (session('status'))
        <div class="mb-4 p-4 rounded-lg bg-green-50 border border-green-200 text-green-800 text-sm">
            {{ session('status') }}
        </div>
    @endif

    <form wire:submit="login" class="space-y-5">
        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-[hsl(var(--foreground))] mb-1.5">
                Email
            </label>
            <input wire:model="email" id="email" type="email" class="input w-full"
                placeholder="email@sekolah.sch.id" required autofocus autocomplete="username" />
            @error('email')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label for="password" class="block text-sm font-medium text-[hsl(var(--foreground))]">
                    Password
                </label>
                <a class="text-sm text-[hsl(var(--muted-foreground))] hover:text-[hsl(var(--foreground))] transition-colors"
                    href="{{ route('password.request') }}" wire:navigate>
                    Lupa password?
                </a>
            </div>
            <input wire:model="password" id="password" type="password" class="input w-full"
                placeholder="Masukkan password" required autocomplete="current-password" />
            @error('password')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <input wire:model="remember" id="remember" type="checkbox"
                class="w-4 h-4 rounded border-gray-300 text-[hsl(var(--primary))] focus:ring-[hsl(var(--primary))]">
            <label for="remember" class="ms-2 text-sm text-[hsl(var(--muted-foreground))]">
                Ingat saya
            </label>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary w-full">
            <svg wire:loading wire:target="login" class="animate-spin -ml-1 mr-2 h-4 w-4" fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                </circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
            </svg>
            Masuk
        </button>
    </form>

    <!-- Back to Home -->
    <p class="mt-6 text-center text-sm text-[hsl(var(--muted-foreground))]">
        <a href="/" class="font-medium text-[hsl(var(--foreground))] hover:underline" wire:navigate>
            ← Kembali ke beranda
        </a>
    </p>
</div>
