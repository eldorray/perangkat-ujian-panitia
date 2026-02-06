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
#[Title('Profil Sekolah')]
class SchoolProfile extends Component
{
    use WithFileUploads;

    public string $nama_sekolah = '';
    public string $npsn = '';
    public string $alamat = '';
    public string $kelurahan = '';
    public string $kecamatan = '';
    public string $kabupaten = '';
    public string $provinsi = '';
    public string $kode_pos = '';
    public string $telepon = '';
    public string $email = '';
    public string $website = '';
    public string $kepala_sekolah = '';
    public string $nip_kepala_sekolah = '';
    public ?string $currentLogo = null;
    public $logo;

    public function mount(): void
    {
        $settings = SchoolSetting::getAllSettings();
        
        $this->nama_sekolah = $settings['nama_sekolah'] ?? '';
        $this->npsn = $settings['npsn'] ?? '';
        $this->alamat = $settings['alamat'] ?? '';
        $this->kelurahan = $settings['kelurahan'] ?? '';
        $this->kecamatan = $settings['kecamatan'] ?? '';
        $this->kabupaten = $settings['kabupaten'] ?? '';
        $this->provinsi = $settings['provinsi'] ?? '';
        $this->kode_pos = $settings['kode_pos'] ?? '';
        $this->telepon = $settings['telepon'] ?? '';
        $this->email = $settings['email'] ?? '';
        $this->website = $settings['website'] ?? '';
        $this->kepala_sekolah = $settings['kepala_sekolah'] ?? '';
        $this->nip_kepala_sekolah = $settings['nip_kepala_sekolah'] ?? '';
        $this->currentLogo = $settings['logo'] ?? null;
    }

    protected array $rules = [
        'nama_sekolah' => 'required|string|max:255',
        'npsn' => 'required|string|max:20',
        'alamat' => 'required|string|max:500',
        'kelurahan' => 'nullable|string|max:100',
        'kecamatan' => 'nullable|string|max:100',
        'kabupaten' => 'nullable|string|max:100',
        'provinsi' => 'nullable|string|max:100',
        'kode_pos' => 'nullable|string|max:10',
        'telepon' => 'nullable|string|max:20',
        'email' => 'nullable|email|max:100',
        'website' => 'nullable|url|max:200',
        'kepala_sekolah' => 'nullable|string|max:255',
        'nip_kepala_sekolah' => 'nullable|string|max:30',
        'logo' => 'nullable|image|max:2048',
    ];

    protected array $messages = [
        'nama_sekolah.required' => 'Nama sekolah wajib diisi.',
        'npsn.required' => 'NPSN wajib diisi.',
        'alamat.required' => 'Alamat wajib diisi.',
        'email.email' => 'Format email tidak valid.',
        'website.url' => 'Format URL website tidak valid.',
        'logo.image' => 'File harus berupa gambar.',
        'logo.max' => 'Ukuran logo maksimal 2MB.',
    ];

    public function save(): void
    {
        $this->validate();

        // Handle logo upload
        if ($this->logo) {
            // Delete old logo if exists
            if ($this->currentLogo && Storage::disk('public')->exists($this->currentLogo)) {
                Storage::disk('public')->delete($this->currentLogo);
            }
            
            $logoPath = $this->logo->store('logos', 'public');
            SchoolSetting::set('logo', $logoPath);
            $this->currentLogo = $logoPath;
            $this->logo = null;
        }

        // Save all settings
        SchoolSetting::set('nama_sekolah', $this->nama_sekolah);
        SchoolSetting::set('npsn', $this->npsn);
        SchoolSetting::set('alamat', $this->alamat);
        SchoolSetting::set('kelurahan', $this->kelurahan);
        SchoolSetting::set('kecamatan', $this->kecamatan);
        SchoolSetting::set('kabupaten', $this->kabupaten);
        SchoolSetting::set('provinsi', $this->provinsi);
        SchoolSetting::set('kode_pos', $this->kode_pos);
        SchoolSetting::set('telepon', $this->telepon);
        SchoolSetting::set('email', $this->email);
        SchoolSetting::set('website', $this->website);
        SchoolSetting::set('kepala_sekolah', $this->kepala_sekolah);
        SchoolSetting::set('nip_kepala_sekolah', $this->nip_kepala_sekolah);

        SchoolSetting::clearCache();

        session()->flash('success', 'Profil sekolah berhasil disimpan.');
    }

    public function removeLogo(): void
    {
        if ($this->currentLogo && Storage::disk('public')->exists($this->currentLogo)) {
            Storage::disk('public')->delete($this->currentLogo);
        }
        
        SchoolSetting::set('logo', null);
        $this->currentLogo = null;
        SchoolSetting::clearCache();
        
        session()->flash('success', 'Logo berhasil dihapus.');
    }

    public function render(): View
    {
        return view('livewire.admin.school-profile');
    }
}
