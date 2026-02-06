# 📋 Perangkat Ujian Panitia

Sistem manajemen perangkat panitia ujian sekolah untuk **PAS** (Penilaian Akhir Semester) dan **PTS** (Penilaian Tengah Semester). Aplikasi ini dirancang untuk mempermudah panitia ujian dalam mempersiapkan seluruh dokumen dan administrasi yang dibutuhkan secara digital.

## ✨ Fitur Utama

### 📊 Data Master
- **Tahun Ajaran** — Kelola tahun ajaran aktif beserta semester
- **Guru / Tenaga Pendidik** — CRUD data guru lengkap (NIP, NUPTK, NIK, gelar) + **Import/Export Excel**
- **Mata Pelajaran** — Kelola data pelajaran (kode, nama, jam/minggu, kelompok, jurusan)
- **Kelas / Rombel** — Kelola data kelas dan tingkat
- **Siswa / Peserta Didik** — CRUD data siswa lengkap (NISN, NIS, data orang tua, KIP) + **Import/Export Excel**
- **Ruang Ujian** — Kelola ruang ujian beserta kapasitas

### 🏫 Profil & Pengaturan
- **Profil Sekolah** — NPSN, alamat lengkap, kepala sekolah, logo sekolah
- **Pengaturan Aplikasi** — Nama aplikasi, logo, favicon (khusus admin)
- **Manajemen User** — CRUD user dengan role `admin` dan `panitia` (khusus admin)

### 📝 Perangkat Ujian (10 Menu)
Setiap kegiatan ujian memiliki **10 menu perangkat** yang dapat dikerjakan secara bertahap:

| No | Menu | Keterangan |
|----|------|------------|
| 1 | **Rencana Anggaran** | RAB lengkap dengan kategori pemasukan (iuran siswa KIP/non-KIP) dan pengeluaran (4 kategori), kalkulasi formula otomatis |
| 2 | **Surat Tugas** | Surat tugas pengawas & pengoreksi dengan pemilihan guru dan tugas tambahan |
| 3 | **Jadwal Ujian** | Jadwal ujian per hari/sesi dengan dukungan **kelompok kelas** (untuk jadwal berbeda per tingkat) |
| 4 | **Penempatan Per Kelas** | Penempatan siswa ke ruang ujian tanpa pengacakan (per rombel) |
| 5 | **Acak Kelas** | Pasangkan 2 kelas → acak siswa lintas ruang ujian untuk mencegah kecurangan |
| 6 | **Kartu Peserta** | Generate & cetak kartu peserta ujian (foto, identitas, ruang, nomor kursi, jadwal) |
| 7 | **Denah Ruang** | Layout kursi ruang ujian dinamis (auto-fit berdasarkan kapasitas) |
| 8 | **Daftar Hadir Peserta** | Daftar hadir siswa per ruang per sesi ujian |
| 9 | **Menu Panitia** | Sub-menu: Daftar Hadir Panitia, Jadwal Mengawas, Berita Acara, Label Amplop Soal, Tata Tertib, Kalkulator Honor, Surat Keputusan, LPJ Panitia |
| 10 | **POS Ujian** | Prosedur Operasional Standar (11 BAB) dengan rich text editor, export ke PDF |

### 🔐 Fitur Khusus
- **Kunci Kegiatan dengan PIN** — Kegiatan ujian dapat dikunci dengan PIN 6 digit untuk mencegah akses tidak sah
- **Algoritma Pengacakan Kelas** — Sistem pasangan kelas dengan distribusi acak siswa ke ruang ujian
- **Kalkulator Honor/Insentif** — Hitung honor panitia dan pengawas berdasarkan kehadiran × tarif
- **Terbilang Otomatis** — Konversi angka ke teks Indonesia (untuk RAB dan dokumen resmi)
- **Rich Text Editor (Quill.js)** — Editor teks kaya untuk POS dan LPJ
- **Export PDF** — Cetak dokumen POS ke format PDF via DomPDF
- **Import/Export Excel** — Template Excel untuk data guru dan siswa

### 🖨️ Dokumen Cetak
- Kartu Peserta Ujian
- Surat Tugas Pengawas & Pengoreksi
- Surat Keputusan Panitia
- Jadwal Ujian
- Laporan Pertanggungjawaban (LPJ)
- Denah Ruang Ujian
- Daftar Hadir Peserta & Panitia
- Berita Acara Ujian
- Label Amplop Soal
- Tata Tertib Ujian
- POS (Prosedur Operasional Standar)

## 🛠️ Tech Stack

| Komponen | Teknologi |
|----------|-----------|
| Framework | Laravel 12 |
| PHP | ≥ 8.2 |
| Database | SQLite (default) / MySQL / PostgreSQL |
| Frontend | Livewire 4, Alpine.js 3, Tailwind CSS |
| Build Tool | Vite 7 |
| PDF Export | barryvdh/laravel-dompdf |
| Excel | maatwebsite/excel |
| Word Export | phpoffice/phpword |
| Rich Text | Quill.js 2 |

## 🚀 Instalasi

### Prasyarat
- PHP ≥ 8.2 (dengan extension: `pdo_sqlite`, `mbstring`, `xml`, `zip`, `gd`)
- Composer
- Node.js ≥ 18 & npm

### Langkah Instalasi

```bash
# 1. Clone repository
git clone https://github.com/eldorray/perangkat-ujian-panitia.git
cd perangkat-ujian-panitia

# 2. Jalankan setup otomatis (install dependencies, generate key, migrate, build assets)
composer setup
```

Atau secara manual:

```bash
# Install PHP dependencies
composer install

# Copy environment file & generate app key
cp .env.example .env
php artisan key:generate

# Buat database SQLite & jalankan migration
touch database/database.sqlite
php artisan migrate

# Install Node dependencies & build assets
npm install
npm run build

# Buat storage link
php artisan storage:link
```

### Menjalankan Aplikasi

```bash
# Mode development (server + vite + queue + logs)
composer dev

# Atau manual
php artisan serve
npm run dev  # (di terminal terpisah)
```

Akses aplikasi di: **http://localhost:8000**

### Membuat Akun Admin Pertama

```bash
php artisan db:seed
```

Login dengan:
- **Email:** `fahmie@gmail.com`
- **Password:** `password`

> ⚠️ **Penting:** Segera ganti password default setelah login pertama kali. Anda juga bisa membuat user baru melalui menu **Manajemen User**.

## 📖 Panduan Penggunaan

### 1. Setup Awal
1. **Login** sebagai admin
2. Buka **Profil Sekolah** → Isi data sekolah (nama, NPSN, alamat, kepala sekolah, logo)
3. Buka **Pengaturan Aplikasi** → Sesuaikan nama dan logo aplikasi (opsional)

### 2. Input Data Master
1. **Tahun Ajaran** → Tambah tahun ajaran aktif (misal: 2025/2026, Ganjil)
2. **Guru** → Input data guru satu per satu atau **Import Excel** menggunakan template
3. **Mata Pelajaran** → Input semua mata pelajaran yang diujikan
4. **Kelas** → Buat semua kelas/rombel
5. **Siswa** → Input data siswa satu per satu atau **Import Excel** menggunakan template
6. **Ruang Ujian** → Tambah ruang ujian beserta kapasitas

### 3. Buat Kegiatan Ujian
1. Buka **Kegiatan Ujian** → Klik **Tambah Kegiatan**
2. Pilih tahun ajaran, isi nama ujian (misal: PAS Ganjil 2025/2026)
3. Klik kegiatan → masuk ke **Perangkat Ujian**

### 4. Persiapan Ujian (Perangkat)
Kerjakan secara berurutan:

1. **📋 Rencana Anggaran** → Buat RAB kegiatan
2. **📄 Surat Tugas** → Pilih guru pengawas & pengoreksi
3. **📅 Jadwal Ujian** → Buat jadwal per hari dan sesi
4. **🪑 Penempatan Siswa** → Pilih salah satu:
   - *Penempatan Per Kelas* — Siswa tetap satu kelas
   - *Acak Kelas* — Pasangkan 2 kelas → acak otomatis
5. **🪪 Kartu Peserta** → Generate dan cetak kartu peserta
6. **🗺️ Denah Ruang** → Lihat dan cetak layout ruang
7. **📋 Daftar Hadir** → Cetak daftar hadir peserta per ruang
8. **👥 Menu Panitia** — Sub-menu:
   - Daftar Hadir Panitia
   - Jadwal Mengawas
   - Berita Acara Ujian
   - Label Amplop Soal
   - Tata Tertib
   - Kalkulator Honor
   - Surat Keputusan (SK) Panitia
   - LPJ Panitia
9. **📘 POS Ujian** → Buat dokumen SOP (11 BAB), export PDF

### 5. Cetak Dokumen
Setiap perangkat memiliki tombol **Cetak** yang akan membuka halaman print di tab baru. Gunakan **Ctrl+P** / **Cmd+P** untuk mencetak atau simpan sebagai PDF.

### 6. Kunci Kegiatan (Opsional)
Setelah semua perangkat selesai, Anda bisa **mengunci** kegiatan ujian dengan PIN 6 digit untuk mencegah perubahan yang tidak disengaja.

## 👥 Role & Hak Akses

| Fitur | Admin | Panitia |
|-------|:-----:|:-------:|
| Data Master (Guru, Siswa, dll) | ✅ | ✅ |
| Kegiatan & Perangkat Ujian | ✅ | ✅ |
| Profil Sekolah | ✅ | ✅ |
| Manajemen User | ✅ | ❌ |
| Pengaturan Aplikasi | ✅ | ❌ |

## 📂 Struktur Project

```
├── app/
│   ├── Exports/          # Template Excel export (Guru, Siswa)
│   ├── Helpers/          # Number helper (terbilang)
│   ├── Http/Controllers/ # Controller untuk cetak dokumen
│   ├── Imports/          # Import Excel (Guru, Siswa)
│   ├── Livewire/Admin/   # 31 Livewire components
│   ├── Models/           # 18 Eloquent models
│   └── View/Components/  # Blade components
├── database/
│   ├── migrations/       # 27 migration files
│   └── seeders/          # Database seeder
├── resources/views/
│   ├── layouts/          # Layout admin
│   ├── livewire/admin/   # Blade views untuk Livewire
│   └── print/            # Template cetak dokumen
├── public/quill/         # Quill.js rich text editor
└── routes/web.php        # Route definitions
```

## 📄 Lisensi

MIT License

## 🙏 Kontribusi

Kontribusi sangat diterima! Silakan buat **Issue** atau **Pull Request** di repository ini.
