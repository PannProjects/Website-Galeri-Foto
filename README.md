<div align="center">

<img src="https://images.unsplash.com/photo-1531747056595-07f6cbbe10ad?w=600&q=80" alt="Galeri Foto Banner" width="700" style="border-radius: 16px; margin-bottom: 24px;" />

# 📷 Website Galeri Foto
### *Latihan UKK (Uji Kompetensi Keahlian) — SMKN 11 Malang*

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![TailwindCSS](https://img.shields.io/badge/TailwindCSS-CDN-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![MariaDB](https://img.shields.io/badge/MariaDB-003545?style=for-the-badge&logo=mariadb&logoColor=white)](https://mariadb.org)

</div>

---

## 📌 Tentang Proyek

**Website Galeri Foto** adalah aplikasi web berbasis **Laravel 12** yang dikembangkan sebagai bagian dari latihan **Uji Kompetensi Keahlian (UKK)** SMKN 11 Malang. Aplikasi ini memungkinkan pengguna untuk mengelola foto pribadi mereka dalam bentuk **album digital** yang terorganisir, dilengkapi dengan fitur komentar dan sistem like.

### 👨‍💻 Informasi Pengembang

| Keterangan | Detail |
|---|---|
| **Nama** | Pandu Setya Wijaya |
| **Kelas** | XII RPL 2 |
| **Sekolah** | SMKN 11 Malang |
| **Program Keahlian** | Rekayasa Perangkat Lunak (RPL) |
| **Jenis Tugas** | Latihan UKK (Uji Kompetensi Keahlian) |

---

## ✨ Fitur Utama

| Fitur | Deskripsi |
|---|---|
| 🔐 **Custom Auth** | Login, Registrasi, dan Logout tanpa Breeze/Jetstream |
| 🗂️ **Manajemen Album** | Buat, lihat, dan hapus album foto |
| 📸 **Manajemen Foto** | Unggah, lihat detail, dan hapus foto |
| ❤️ **Sistem Like** | Like/unlike foto dengan toggle |
| 💬 **Sistem Komentar** | Tambah dan hapus komentar pada foto |
| 📱 **Responsif** | Tampilan optimal di mobile, tablet, dan desktop |
| 🍎 **iOS UI** | Desain glassmorphism terinspirasi antarmuka iOS |

---

## 🛠️ Teknologi yang Digunakan

- **Backend:** Laravel 12, PHP 8.2+
- **Database:** MariaDB / MySQL
- **Frontend:** Blade Templating + TailwindCSS (via CDN)
- **Font:** Google Font — Poppins
- **Storage:** Laravel Storage (disk `public`)
- **Autentikasi:** Custom Auth (manual session, tanpa package tambahan)

---

## 🗄️ Struktur Database

```
gallery_user          → Data akun pengguna
   └── gallery_album  → Album foto milik pengguna
        └── gallery_foto → Foto di dalam album
             ├── gallery_komentarfoto → Komentar pada foto
             └── gallery_likefoto     → Like pada foto
```

### Relasi Tabel:
- `gallery_user` **hasMany** `gallery_album`, `gallery_foto`, `gallery_komentarfoto`, `gallery_likefoto`
- `gallery_album` **belongsTo** `gallery_user` | **hasMany** `gallery_foto`
- `gallery_foto` **belongsTo** `gallery_user`, `gallery_album` | **hasMany** `gallery_komentarfoto`, `gallery_likefoto`
- `gallery_komentarfoto` **belongsTo** `gallery_foto`, `gallery_user`
- `gallery_likefoto` **belongsTo** `gallery_foto`, `gallery_user`

---

## 🚀 Cara Menjalankan Proyek

### Prasyarat
Pastikan sudah terinstal di komputer Anda:
- ✅ PHP >= 8.2
- ✅ Composer
- ✅ MariaDB / MySQL
- ✅ Git

### Langkah-langkah Instalasi

```bash
# 1. Clone repositori ini
git clone <url-repositori> website-galeri-foto
cd website-galeri-foto

# 2. Install dependensi PHP
composer install

# 3. Salin file konfigurasi environment
cp .env.example .env

# 4. Generate application key
php artisan key:generate
```

### Konfigurasi Database

Edit file `.env` dan sesuaikan pengaturan database:

```env
APP_NAME="Galeri Foto"

DB_CONNECTION=mariadb
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=website_galeri_foto   # Buat database ini terlebih dahulu
DB_USERNAME=root
DB_PASSWORD=                      # Sesuaikan password database Anda
```

### Menjalankan Migrasi, Seeder & Storage

```bash
# 5. Buat database di MariaDB/MySQL terlebih dahulu, lalu:
php artisan migrate

# 6. Isi data akun default (seeder)
php artisan db:seed

# 7. Buat symbolic link untuk storage foto
php artisan storage:link
```

### Menjalankan Server

```bash
# 8. Jalankan server development Laravel
php artisan serve
```

Buka browser dan akses: **[http://localhost:8000](http://localhost:8000)**

> 💡 **Shortcut reset penuh:** `php artisan migrate:fresh --seed` — menghapus semua tabel, migrasi ulang, lalu seed otomatis.

---

## 🔑 Akun Default (Seeder)

Setelah menjalankan `php artisan db:seed`, akun berikut sudah tersedia:

| Field | Nilai |
|---|---|
| **Nama Lengkap** | Pandu Setya |
| **Username** | `PannProjects` |
| **Email** | pandumalang321@gmail.com |
| **Password** | `pandusetya` |
| **Alamat** | Jl. Pelabuhan Ketapang 1 |

> ⚠️ Segera ganti password setelah pertama kali login di lingkungan produksi.

---

## 📂 Struktur Direktori Penting

```
website-galeri-foto/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php       # Custom authentication
│   │   │   ├── DashboardController.php  # Halaman galeri utama
│   │   │   ├── AlbumController.php      # Manajemen album
│   │   │   ├── FotoController.php       # Manajemen foto & upload
│   │   │   ├── KomentarController.php   # Sistem komentar
│   │   │   └── LikeController.php       # Sistem like/unlike
│   │   └── Middleware/
│   │       └── CekAuth.php              # Middleware autentikasi kustom
│   └── Models/
│       ├── GalleryUser.php
│       ├── GalleryAlbum.php
│       ├── GalleryFoto.php
│       ├── GalleryKomentarFoto.php
│       └── GalleryLikeFoto.php
├── database/
│   ├── migrations/                      # File migrasi tabel
│   └── seeders/
│       ├── DatabaseSeeder.php           # Entry point seeder
│       └── UserSeeder.php               # Seeder akun default
├── resources/views/
│   ├── layouts/app.blade.php            # Layout utama (glassmorphism navbar)
│   ├── auth/
│   │   ├── login.blade.php              # Halaman login
│   │   └── register.blade.php           # Halaman registrasi
│   ├── dashboard.blade.php              # Galeri foto utama
│   ├── foto/
│   │   ├── create.blade.php             # Form upload foto
│   │   └── show.blade.php               # Detail foto + komentar + like
│   └── album/
│       ├── index.blade.php              # Daftar album
│       └── create.blade.php             # Form buat album
└── routes/web.php                       # Definisi semua rute
```

---

## 📸 Cara Penggunaan

1. **Daftar akun** di halaman `/register`
2. **Login** dengan username dan password yang telah didaftarkan
3. **Buat album** di menu Album → Buat Album Baru
4. **Unggah foto** ke dalam album yang sudah dibuat
5. **Lihat detail foto** dengan klik pada foto di galeri
6. **Beri komentar** atau **like** pada foto
7. **Hapus foto atau album** yang tidak diperlukan

---

## 📝 Catatan Teknis

> **Node.js & npm tidak digunakan** pada proyek ini. TailwindCSS diintegrasikan melalui CDN langsung di file Blade untuk memenuhi persyaratan proyek.

> **Session** menggunakan driver `file` (tidak membutuhkan tabel sessions di database).

> **File foto** disimpan di `storage/app/public/fotos/` dan diakses melalui `storage/` symlink.

> **Seeder** menggunakan `firstOrCreate` sehingga aman dijalankan berulang kali tanpa duplikasi data.

---

<div align="center">

**© 2026 Pandu Setya Wijaya · XII RPL 2 · SMKN 11 Malang**

*Dibuat dengan ❤️ menggunakan Laravel & TailwindCSS*

</div>
