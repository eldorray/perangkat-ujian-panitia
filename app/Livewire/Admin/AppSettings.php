<?php

namespace App\Livewire\Admin;

use App\Models\SchoolSetting;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

#[Layout('layouts.admin')]
#[Title('Pengaturan Aplikasi')]
class AppSettings extends Component
{
    use WithFileUploads;

    public ?string $currentAppLogo = null;
    public ?string $currentFavicon = null;
    public ?string $appName = '';
    public $appLogo;
    public $favicon;

    public function mount(): void
    {
        $settings = SchoolSetting::getAllSettings();

        $this->currentAppLogo = $settings['app_logo'] ?? null;
        $this->currentFavicon = $settings['favicon'] ?? null;
        $this->appName = $settings['app_name'] ?? 'Perangkat Panitia Ujian';
    }

    protected array $rules = [
        'appName' => 'required|string|max:255',
        'appLogo' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
        'favicon' => 'nullable|image|mimes:ico,png|max:512',
    ];

    protected array $messages = [
        'appName.required' => 'Nama aplikasi wajib diisi.',
        'appLogo.image' => 'File harus berupa gambar.',
        'appLogo.mimes' => 'Logo harus berformat PNG, JPG, JPEG, atau SVG.',
        'appLogo.max' => 'Ukuran logo maksimal 2MB.',
        'favicon.image' => 'File harus berupa gambar.',
        'favicon.mimes' => 'Favicon harus berformat ICO atau PNG.',
        'favicon.max' => 'Ukuran favicon maksimal 512KB.',
    ];

    public function save(): void
    {
        $this->validate();

        // Handle app logo upload
        if ($this->appLogo) {
            // Delete old logo if exists
            if ($this->currentAppLogo && Storage::disk('public')->exists($this->currentAppLogo)) {
                Storage::disk('public')->delete($this->currentAppLogo);
            }

            $logoPath = $this->appLogo->store('app', 'public');
            SchoolSetting::set('app_logo', $logoPath);
            $this->currentAppLogo = $logoPath;
            $this->appLogo = null;
        }

        // Handle favicon upload
        if ($this->favicon) {
            // Delete old favicon if exists
            if ($this->currentFavicon && Storage::disk('public')->exists($this->currentFavicon)) {
                Storage::disk('public')->delete($this->currentFavicon);
            }

            $faviconPath = $this->favicon->store('app', 'public');
            SchoolSetting::set('favicon', $faviconPath);
            $this->currentFavicon = $faviconPath;
            $this->favicon = null;
        }

        // Save app name
        SchoolSetting::set('app_name', $this->appName);

        // Clear cache
        SchoolSetting::clearCache();

        session()->flash('success', 'Pengaturan aplikasi berhasil disimpan.');
    }

    public function removeAppLogo(): void
    {
        if ($this->currentAppLogo && Storage::disk('public')->exists($this->currentAppLogo)) {
            Storage::disk('public')->delete($this->currentAppLogo);
        }

        SchoolSetting::set('app_logo', null);
        $this->currentAppLogo = null;
        SchoolSetting::clearCache();

        session()->flash('success', 'Logo aplikasi berhasil dihapus.');
    }

    public function removeFavicon(): void
    {
        if ($this->currentFavicon && Storage::disk('public')->exists($this->currentFavicon)) {
            Storage::disk('public')->delete($this->currentFavicon);
        }

        SchoolSetting::set('favicon', null);
        $this->currentFavicon = null;
        SchoolSetting::clearCache();

        session()->flash('success', 'Favicon berhasil dihapus.');
    }

    public function render(): View
    {
        return view('livewire.admin.app-settings');
    }
}
