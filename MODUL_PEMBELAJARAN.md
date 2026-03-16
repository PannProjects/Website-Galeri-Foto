# 📚 Modul Pembelajaran Laravel: Bedah Proyek Galeri Foto UKK

> **Untuk siapa modul ini?**
> Modul ini ditulis untuk kamu yang **baru pertama kali** membuka proyek Laravel dan merasa bingung "ini folder apa, file ini ngapain?". Tenang — kita akan bedah semuanya bersama, pelan-pelan, dengan bahasa yang santai.

---

## Bab 1 — Apa Itu Laravel dan Bagaimana Cara Kerjanya?

### 🍽️ Analogi: Laravel adalah Sebuah Restoran

Bayangkan kamu pergi ke sebuah restoran mewah. Inilah yang terjadi:

1. **Kamu (Pelanggan)** → datang dan berteriak *"Saya mau pesan Nasi Goreng!"*
2. **Pelayan (Routes)** → mendengar pesananmu, lalu pergi ke dapur yang tepat.
3. **Kepala Dapur (Controller)** → menerima pesanan, menyiapkan bahan, dan mengatur semuanya.
4. **Buku Resep (Model)** → memberi tahu kepala dapur cara mengambil bahan dari gudang (database).
5. **Piring Makanan Jadi (View)** → hasil akhir yang disajikan ke mejamu — itulah halaman web yang kamu lihat di browser.

Dalam dunia Laravel, pola kerja ini disebut **MVC** (Model - View - Controller):

| Istilah Laravel | Analogi Restoran | Fungsi Nyata |
|---|---|---|
| **Routes** (`web.php`) | Pelayan | Menentukan: "URL ini ditangani siapa?" |
| **Controller** | Kepala Dapur | Memproses logika, mengambil data, memutuskan tampilan |
| **Model** | Buku Resep + Gudang | Berkomunikasi dengan database (ambil/simpan/hapus data) |
| **View** (Blade) | Piring Sajian | Tampilan HTML yang dilihat pengguna di browser |
| **Database** | Gudang Bahan | Tempat semua data tersimpan permanen |

---

## Bab 2 — Peta Proyek (Bedah Struktur Folder)

Saat kamu membuka proyek ini, akan terlihat banyak sekali folder. Kamu **tidak perlu** memahami semuanya sekarang. Fokus saja pada folder-folder ini:

```
website-galeri-foto/
│
├── 📂 routes/
│   └── web.php          ← MULAI dari sini. Semua "alamat URL" didaftarkan di sini.
│
├── 📂 app/
│   ├── 📂 Http/
│   │   ├── 📂 Controllers/  ← "Kepala Dapur" — logika aplikasi ada di sini
│   │   │   ├── AuthController.php      (login, daftar, keluar)
│   │   │   ├── DashboardController.php (halaman galeri utama)
│   │   │   ├── AlbumController.php     (kelola album)
│   │   │   ├── FotoController.php      (upload & hapus foto)
│   │   │   ├── KomentarController.php  (sistem komentar)
│   │   │   └── LikeController.php      (sistem like)
│   │   │
│   │   └── 📂 Middleware/
│   │       └── CekAuth.php  ← "Satpam" — cek apakah user sudah login
│   │
│   └── 📂 Models/           ← "Buku Resep" — mewakili tabel di database
│       ├── GalleryUser.php
│       ├── GalleryAlbum.php
│       ├── GalleryFoto.php
│       ├── GalleryKomentarFoto.php
│       └── GalleryLikeFoto.php
│
├── 📂 database/
│   └── 📂 migrations/   ← "Cetak Biru" tabel database. Dijalankan sekali.
│
└── 📂 resources/
    └── 📂 views/        ← "Piring Sajian" — file HTML/Blade tampilan pengguna
        ├── layouts/
        │   └── app.blade.php       (template induk: navbar, footer)
        ├── auth/
        │   ├── login.blade.php     (halaman login)
        │   └── register.blade.php  (halaman daftar)
        ├── dashboard.blade.php     (galeri foto utama)
        ├── foto/
        │   ├── create.blade.php    (form upload foto)
        │   └── show.blade.php      (detail foto)
        └── album/
            ├── index.blade.php     (daftar album)
            └── create.blade.php    (form buat album)
```

> 💡 **Folder yang BOLEH DIABAIKAN dulu:** `bootstrap/`, `config/`, `storage/`, `tests/`, `vendor/`. Folder-folder itu dikelola sistem, bukan kamu.

---

## Bab 3 — Alur Kerja: Dari URL ke Halaman Web

Sekarang kita ikuti perjalanan sebuah permintaan dari awal sampai akhir. Contoh: **User membuka `/dashboard`**.

```
┌─────────────────────────────────────────────────────────────────────┐
│                                                                     │
│  1. USER mengetik: http://localhost:8000/dashboard di browser       │
│                            ↓                                        │
│  2. ROUTES (web.php) membaca: "Oh, /dashboard? Panggil             │
│     DashboardController, method index()"                            │
│                            ↓                                        │
│  3. MIDDLEWARE (CekAuth) menghadang: "Hei, kamu sudah login?        │
│     Cek dulu session-nya..." → Jika belum, redirect ke /login       │
│                            ↓  (jika sudah login, lanjut)           │
│  4. CONTROLLER (DashboardController@index) bekerja:                 │
│     "Ambil semua foto milik user ini dari database"                 │
│                            ↓                                        │
│  5. MODEL (GalleryFoto) ke DATABASE: "SELECT * FROM gallery_foto    │
│     WHERE UserID = ..." → Data dikembalikan ke Controller           │
│                            ↓                                        │
│  6. CONTROLLER mengirim data ke VIEW: return view('dashboard',      │
│     ['fotos' => $fotos])                                            │
│                            ↓                                        │
│  7. VIEW (dashboard.blade.php) merender HTML dengan data tersebut   │
│                            ↓                                        │
│  8. BROWSER menampilkan halaman galeri foto yang indah ✅           │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

---

## Bab 4 — Bedah Kode Utama Baris Per Baris

Kita akan bedah 3 file kunci: **Routes**, **Controller (Auth)**, dan **Model**.

---

### 4.1 — Bedah `routes/web.php` (Si Pelayan)

```php
<?php

// ① Impor "kelas" Controller yang akan dipakai
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AlbumController;
// ...dan seterusnya

// ② Rute pertama: halaman utama "/"
// Sintaks `Route::get` punya 2 bagian: (1) URL tujuan, (2) Apa yang dijalankan
Route::get('/', function () { 
    // `session('user_id')` artinya mengecek apakah di memory server ada variabel 'user_id'
    if (session('user_id')) {
        // `redirect()->route('dashboard')` artinya suruh browser pindah ke halaman dengan nama alias 'dashboard'
        return redirect()->route('dashboard'); 
    }
    return redirect()->route('login'); // Belum login → ke halaman login
});
```

**Penjelasan ①:** Ibarat sebelum memanggil tukang masak, kita perlu tahu dulu tukang masak mana yang kita maksud. `use App\Http\Controllers\AuthController` artinya: *"Saya akan pakai AuthController dari folder app/Http/Controllers"*.

**Penjelasan ②:** `Route::get('/', ...)` artinya: *"Kalau ada orang yang membuka halaman `/` dengan metode GET (buka biasa lewat browser), jalankan kode di dalam kurung kurawal ini"*.

---

```php
// ③ Rute publik — siapa saja boleh akses, tidak perlu login
// Sintaks: Route::[method HTTP]( '[URL]', [ [NamaController]::class, '[Nama Fungsi di Controller]' ] )->name('[Nama Alias]');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
```

**Penjelasan ③:**
- `Route::get('/login', ...)` → Saat browser **membuka** halaman `/login`, panggil method `showLogin` di `AuthController`.
- `Route::post('/login', ...)` → Saat form login **dikirim** (klik tombol "Masuk"), panggil method `login` di `AuthController`.
- **GET vs POST:** `GET` = mengambil/melihat data. `POST` = mengirim/memproses data.
- `->name('login')` → Memberi nama alias pada rute, sehingga kita bisa memanggilnya dengan `route('login')` di mana saja tanpa perlu menulis URL `/login` secara manual.

---

```php
// ④ Rute terproteksi — wajib login dulu!
// Sintaks `Route::middleware('nama_middleware')->group(...)` akan membungkus semua rute di dalamnya dengan aturan satpam.
Route::middleware('auth.galeri')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/album', [AlbumController::class, 'index'])->name('album.index');
    // ...rute lainnya...

});
```

**Penjelasan ④:** `middleware('auth.galeri')` adalah **"Satpam Digital"**. Semua rute yang dibungkus di dalam `group(function () { ... })` ini hanya bisa diakses jika sudah melewati pemeriksaan satpam. Satpamnya adalah file `CekAuth.php` — yang tugasnya cuma mengecek: *"Ada session `user_id` tidak?"*. Tidak ada? Langsung diusir ke halaman login.

---

### 4.2 — Bedah `AuthController.php` (Si Kepala Dapur)

```php
<?php

namespace App\Http\Controllers; // ① Ini "alamat" file ini dalam proyek

use App\Models\GalleryUser;      // ② Pakai model GalleryUser (untuk akses tabel)
use Illuminate\Http\Request;     // ② Pakai Request (untuk membaca data kiriman form)
use Illuminate\Support\Facades\Hash; // ② Pakai Hash (untuk enkripsi password)

class AuthController extends Controller // ③ Definisi kelas
{
```

**Penjelasan ①:** `namespace` adalah seperti **alamat rumah**. Ia memberi tahu Laravel *"file ini tinggal di App\Http\Controllers"*. Ini penting agar Laravel tidak bingung jika ada dua file dengan nama yang sama di folder berbeda.

**Penjelasan ②:** `use` adalah cara kita "meminjam" alat dari tempat lain. Seperti memanggil asisten khusus sebelum mulai bekerja.

**Penjelasan ③:** `extends Controller` artinya `AuthController` mewarisi kemampuan dasar dari kelas induk `Controller`. Seperti seorang koki magang yang belajar dari koki senior.

---

```php
    // Method ini dipanggil saat user membuka /login (GET)
    public function showLogin()
    {
        // ④ Jika sudah login, tidak perlu ke halaman login lagi
        if (session('user_id')) {
            return redirect()->route('dashboard');
        }
        // ⑤ Tampilkan file views/auth/login.blade.php
        return view('auth.login');
    }
```

**Penjelasan ④:** `session('user_id')` = membaca "tiket masuk" si user. Kalau tiket ada, ya langsung masuk saja, tidak perlu antre lagi di loket login.

**Penjelasan ⑤:** `return view('auth.login')` = *"Ambil file Blade bernama `login.blade.php` di dalam folder `auth`, lalu tampilkan ke browser"*. Titik (`.`) dalam nama view menggantikan slash (`/`) dalam nama folder.

---

```php
    // Method ini dipanggil saat form login dikirim (POST)
    public function login(Request $request)
    {
        // ⑥ Validasi: pastikan form diisi dengan benar
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);
```

**Penjelasan ⑥:** `$request->validate(...)` adalah **satpam form**. Sebelum data diproses, ia memeriksa dulu apakah semua syarat terpenuhi. Format aturannya dipisahkan `|` (pipe). Contoh: `'required|string'` artinya *"wajib diisi DAN harus berupa teks"*. Jika gagal validasi, Laravel otomatis mengembalikan user ke halaman sebelumnya beserta pesan error — tanpa kita perlu kode tambahan.

---

```php
        // ⑦ Cari user di database berdasarkan username
        // Sintaks Model: [NamaModel]::where('[Nama Kolom di DB]', [Nilai yang dicari])->first();
        // `->first()` artinya ambil 1 data saja (karena username unik)
        $user = GalleryUser::where('Username', $request->username)->first();

        // ⑧ Periksa: user ada? dan password cocok?
        // `!` (tanda seru) di PHP artinya "BUKAN" atau "TIDAK"
        // `!$user` = Jika user TIDAK ada.
        // `||` = ATAU
        // `!Hash::check(...)` = Jika password TIDAK cocok.
        if (!$user || !Hash::check($request->password, $user->Password)) {
            // `back()` suruh browser mundur ke belakang (halaman login)
            return back()
                ->withInput(['username' => $request->username])
                ->withErrors(['login' => 'Username atau password yang Anda masukkan salah.']);
        }
```

**Penjelasan ⑦:** `GalleryUser::where('Username', $request->username)->first()` dibaca seperti kalimat SQL: *"Dari tabel gallery_user, cari baris di mana kolom Username sama dengan yang diketik user, ambil yang pertama"*. Hasilnya disimpan di variabel `$user`. Jika tidak ditemukan, `$user` bernilai `null`.

**Penjelasan ⑧:**
- `!$user` = Jika user **tidak** ditemukan (null).
- `!Hash::check(...)` = Jika password yang diketik **tidak** cocok dengan password terenkripsi di database.
- `return back()` = Kembali ke halaman login.
- `->withErrors(...)` = Bawa pesan error agar bisa ditampilkan di view.

> 🔐 **Kenapa pakai `Hash::check` bukan perbandingan biasa?**
> Password di database disimpan dalam bentuk **terenkripsi** (diacak), misalnya `$2y$12$...`. Kita tidak bisa membandingkan langsung. `Hash::check` bertugas mengecek apakah password asli (yang diketik) cocok dengan versi terenkripsinya.

---

```php
        // ⑨ Simpan data login ke session (seperti memberikan "tiket masuk")
        $request->session()->put('user_id', $user->UserID);
        $request->session()->put('user_nama', $user->NamaLengkap);
        $request->session()->put('user_username', $user->Username);

        // ⑩ Perbarui ID session untuk keamanan
        $request->session()->regenerate();

        // ⑪ Redirect ke dashboard dan kirim pesan sukses
        return redirect()->route('dashboard')
            ->with('sukses', 'Selamat datang kembali, ' . $user->NamaLengkap . '!');
    }
```

**Penjelasan ⑨:** `session()->put('kunci', 'nilai')` = Menyimpan informasi ke dalam **session** (memori sementara yang diingat server selama browser kamu terbuka). Analoginya: seperti server menaruh **gelang tamu** di pergelangan tanganmu di pintu masuk festival. Selama gelang ada, kamu bebas masuk ke area mana saja.

**Penjelasan ⑩:** `regenerate()` mengganti ID session lama dengan yang baru setelah login. Ini teknik keamanan untuk mencegah **Session Fixation Attack** (serangan di mana penjahat mencuri ID session sebelum login dilakukan).

**Penjelasan ⑪:** `->with('sukses', 'pesan...')` = Mengirim pesan kilat (flash message) ke halaman tujuan. Pesan ini hanya muncul sekali, lalu hilang saat halaman di-refresh.

---

### 4.3 — Bedah `GalleryUser.php` (Si Model / Buku Resep)

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryUser extends Model
{
    // ① Tunjukkan nama tabel yang benar di database
    protected $table = 'gallery_user';

    // ② Tentukan kolom primary key-nya
    protected $primaryKey = 'UserID';

    // ③ Kolom mana saja yang boleh diisi lewat kode (mass assignment)
    protected $fillable = [
        'Username', 'Password', 'Email', 'NamaLengkap', 'Alamat',
    ];

    // ④ Kolom yang DISEMBUNYIKAN saat data diubah ke JSON/array
    protected $hidden = ['Password'];
```

**Penjelasan ①:** Secara default, Laravel menebak nama tabel dari nama Model (contoh: Model `User` → tabel `users`). Karena nama tabel kita `gallery_user` (tidak mengikuti konvensi bawaan), kita harus memberitahu secara eksplisit dengan `protected $table`.

**Penjelasan ②:** Sama seperti ①, Laravel secara default mengasumsikan primary key bernama `id`. Kita override dengan `'UserID'`.

**Penjelasan ③:** `$fillable` adalah **daftar putih kolom** yang boleh diisi sekaligus (mass assignment). Ini mencegah bug keamanan **Mass Assignment Vulnerability** — tanpa ini, seseorang bisa mengirim data jahat lewat form dan mengubah kolom yang tidak seharusnya.

**Penjelasan ④:** `$hidden` memastikan kolom `Password` tidak pernah bocor saat data user dikirim sebagai JSON ke API atau ditampilkan secara tidak sengaja.

---

```php
    // ⑤ Relasi: Satu user MEMILIKI BANYAK album
    // Sintaks: return $this->[Jenis Relasi]( [Model Tujuan]::class, '[Foreign Key di Tabel Tujuan]', '[Primary Key di Model ini]' );
    public function albums()
    {
        return $this->hasMany(GalleryAlbum::class, 'UserID', 'UserID');
    }

    // ⑤ Relasi: Satu user MEMILIKI BANYAK foto
    public function fotos()
    {
        return $this->hasMany(GalleryFoto::class, 'UserID', 'UserID');
    }
}
```

**Penjelasan ⑤ — Relasi Eloquent:** Ini adalah fitur paling "sihir" di Laravel. Dengan mendefinisikan `hasMany`, kita bisa mengambil semua album milik seorang user hanya dengan:

```php
// Kita memanggil relasi SEBAGAI PROPERTY (tanpa tanda kurung () di akhirnya)
$user->albums  // ← Langsung dapat semua album! Tanpa SQL manual.
```

Di balik layar, Laravel otomatis menjalankan:
```sql
SELECT * FROM gallery_album WHERE UserID = [id user ini]
```

Kebalikannya adalah `belongsTo` — dipakai di Model `GalleryAlbum` untuk berkata *"Album ini DIMILIKI OLEH satu user"*.

---

## Bab 6 — View & Blade Template Engine (Si Piring Sajian)

Di Laravel, file HTML tidak berakhiran `.html`, melainkan `.blade.php`. Kenapa? Karena Laravel punya "mesin ajaib" bernama **Blade** yang membuat penulisan HTML jadi lebih pintar dan mudah.

### 6.1 — Menampilkan Data (Echo)

Di PHP biasa, kamu menampilkan data seperti ini:
```php
<?php echo $user->nama; ?>
```

Dengan Blade, cukup seperti ini:
```blade
{{ $user->nama }}
```
Tanda `{{ }}` secara otomatis membersihkan kode jahat (XSS protection), jadi sangat aman dari serangan injeksi!

### 6.2 — Logika Tanpa Ribet (If & Foreach)

Blade mengubah logika PHP yang rumit menjadi perintah simpel diawali tanda `@`.

**Contoh IF (Percabangan):**
```blade
@if(session('sukses'))
    <div class="alert-hijau">
        {{ session('sukses') }}
    </div>
@endif
```

**Contoh FOREACH (Perulangan):**
Ini sangat sering dipakai untuk menampilkan banyak data dari database, misalnya daftar foto!
```blade
@foreach($fotos as $foto)
    <img src="{{ asset('storage/' . $foto->LokasiFile) }}" alt="Foto">
    <p>{{ $foto->JudulFoto }}</p>
@endforeach
```

### 6.3 — Layout Master & Anak (Inheritance)

Bayangkan sebuah web punya Navbar dan Footer yang sama di setiap halaman. Di HTML biasa, kamu harus copy-paste kode itu ke semua file. Di Blade? Tidak perlu!

**1. File Master (`resources/views/layouts/app.blade.php`):**
```blade
<html>
<head><title>Galeri Web</title></head>
<body>
    @include('layouts.navbar') <!-- Panggil potongan navbar dari file lain -->

    <!-- Di sinilah konten unik tiap halaman akan masuk -->
    @yield('content')

    @include('layouts.footer') <!-- Panggil potongan footer -->
</body>
</html>
```

**2. File Anak (`resources/views/dashboard.blade.php`):**
```blade
@extends('layouts.app') <!-- "Saya pakai master template app.blade!" -->

@section('content')
    <!-- Kode HTML khusus halaman ini saja -->
    <h1>Selamat datang di Galeri</h1>
@endsection
```

### 6.4 — Form dan Keamanan (CSRF)

Setiap kali kamu membuat form (`POST`) di Laravel, **WAJIB** menambahkan `@csrf` di dalam form.

```blade
<form action="/login" method="POST">
    @csrf <!-- INI WAJIB ADA! -->
    <input type="text" name="username">
    <button type="submit">Masuk</button>
</form>
```
**Kenapa WAJIB?** `@csrf` (Cross-Site Request Forgery) meng-generate token rahasia untuk memastikan form itu benar-benar dikirim dari web kita secara sah, bukan dari hacker yang mencoba mengirim formulir palsu bertopeng aplikasi kita.

---

## Bab 7 — Migration (Si Cetak Biru Database)

Zaman dulu, kalau membuat tabel database kita harus buka PHPMyAdmin dan klik-klik buat tabel satu-satu. Repot kalau kerja tim atau pindah komputer/server!

Laravel memecahkan masalah ini dengan **Migration**. Ini adalah *kode PHP yang bertugas mengontrol struktur tabel database*. Letaknya di `database/migrations/`.

### 7.1 — Bedah Sintaks File Migration

Mari lihat contoh file migration untuk foto (misal: `...create_gallery_foto_table.php`):

```php
class CreateGalleryFotoTable extends Migration
{
    // Fungsi `up()` akan dijalankan saat kita mengetik "php artisan migrate" di terminal
    public function up(): void 
    {
        // `Schema::create` bertugas membuat tabel baru
        // Argumen 1: 'gallery_foto' (Nama tabel)
        // Argumen 2: function (Blueprint $table) -> Kotak perkakas untuk mendefinisikan kolom
        Schema::create('gallery_foto', function (Blueprint $table) {
            
            // `$table->id('FotoID')` membikin kolom 'FotoID' sebagai Primary Key yang otomatis tambah 1 (Auto Increment)
            $table->id('FotoID'); 
            
            // `$table->string('JudulFoto', 255)` membuat kolom VARCHAR dengan batas maksimal 255 karakter
            $table->string('JudulFoto', 255); 
            
            // `->nullable()` di ujung artinya kolom ini BOLEH KOSONG saat disave ke database
            $table->text('DeskripsiFoto')->nullable(); 
            
            // `$table->date` untuk tipe data kalender/tanggal
            $table->date('TanggalUnggah'); 
            
            $table->string('LokasiFile', 255); 
            
            // `unsignedBigInteger` adalah tipe data angka khusus untuk Foreign Key 
            // Angka 'unsigned' artinya angkanya tidak boleh negatif (minus), cocok untuk ID
            $table->unsignedBigInteger('AlbumID');
            $table->unsignedBigInteger('UserID');
            
            // `$table->timestamps()` adalah sintaks ajaib Laravel yang OTOMATIS membuat 2 kolom:
            // 1. `created_at` (Kapan data pertama kali disimpan)
            // 2. `updated_at` (Kapan data terakhir di-edit)
            $table->timestamps(); 
        });
    }

    // Fungsi `down()` adalah kebalikan `up()`, dijalankan kalau kita ingin MENGHAPUS/MEMBATALKAN tabel
    // lewat perintah "php artisan migrate:rollback"
    public function down(): void
    {
        Schema::dropIfExists('gallery_foto'); // Hancurkan tabel gallery_foto jika memang ada
    }
}
```

### 7.2 — Menjalankan Migration (Blueprint)

Setelah blueprint bahasa PHP selesai ditulis, kita memberi perintah pada Laravel agar menerjemahkannya jadi tabel SQL nyata di database memakai terminal:

```bash
php artisan migrate
```
Jika ada kesalahan fatal tabel waktu masa development aplikasi, kita bisa menghancurkan semua tabel dan me-remake-nya dari mula dengan:
```bash
php artisan migrate:fresh
```

---

## Bab 8 — Mengelola File Gambar (Storage)

Bagaimana cara foto-foto yang kamu upload disimpan? Di database, kita **HANYA** menyimpan sepotong nama path/lokasi file-nya saja (contoh string teks: `foto/random-nama-file.jpg`), sedangkan **file asli (gambar jpeg/png-nya)** disimpan di folder penyimpanan server.

### 8.1 — File System Laravel (Folder Storage)

Secara bawaan, Laravel mengamankan file upload (gambar) di folder rahasia: `storage/app/public/`.
Folder ini sengaja tidak dapat dicari langsung pakai link URL browser demi alasan penjagaan data.

**Tapi tunggu!** Bukankah web galeri foto butuh memunculkan gambar buat diakses publik dari address web browser? 
Supaya isinya bisa ditampilkan, kita menggunakan komando **Symbolic Link** (Jalan Pintas / Shortcut).

```bash
php artisan storage:link
```
Perintah ini membikin sebuah alias shortcut dari folder `/public/storage` supaya menembus langsung memantulkan file yang bersembunyi di `/storage/app/public/`. Barulah web kamu bisa panggil (`<img src="{{ asset('storage/foto/...jpg') }}">`).

### 8.2 — Menyimpan File Foto lewat Controller

Proses simpan upload bekerja sangat sakti dan ringkas dengan Controller (`FotoController.php` misalnya):

```php
// 1. Tangkap inputan file tipe gambar bernama form 'foto'
$file = $request->file('foto');

// 2. Simpan file asli tersebut ke partisi 'public' dalam sub-folder bernama 'foto'
$path = $file->store('foto', 'public');
// Note: $path sekarang berisi string contohnya -> "foto/abc-gambar123.jpg"

// 3. Simpan data-data teks dan alamat string tersebut ke record database baru
GalleryFoto::create([
    'JudulFoto' => $request->JudulFoto,
    'LokasiFile' => $path, // <- Simpan alamat teks string-nya ke tabel
    'UserID' => session('user_id'),
    // ... baris-baris data sisanya
]);
```

---

## Bab 9 — Kesimpulan: Rangkuman Alur Lengkap

Selamat! Sekarang kamu sudah memahami keseluruhan alur proyek ini. Mari kita rangkum:

```
URL di Browser
      ↓
  web.php (Routes)
  "Siapa yang menangani URL ini?"
      ↓
  Middleware CekAuth
  "Apakah user sudah login? (Cek session)"
      ↓ (jika ya)
  Controller
  "Ambil data dari Model, putuskan view mana yang ditampilkan"
      ↓
  Model (+ Database)
  "Ambil / simpan / hapus data dari tabel database"
      ↓
  Controller mengirim data ke View
  return view('nama-file', ['data' => $data])
      ↓
  View (.blade.php)
  "Render HTML dengan data yang diterima"
      ↓
  Halaman Web Muncul di Browser ✅
```

### Kata Kunci yang Wajib Diingat

| Kata Kunci | Artinya |
|---|---|
| `Route::get()` | Tangkap permintaan buka halaman (GET) |
| `Route::post()` | Tangkap permintaan kirim form (POST) |
| `->name()` | Beri nama alias pada rute |
| `middleware()` | Pasang "satpam" sebelum rute bisa diakses |
| `return view()` | Tampilkan file Blade ke browser |
| `$request->validate()` | Periksa isi form sebelum diproses |
| `session()->put()` | Simpan data sementara (tiket masuk) |
| `session('kunci')` | Ambil data dari session |
| `redirect()->route()` | Arahkan user ke halaman lain |
| `Hash::make()` | Enkripsi password sebelum disimpan |
| `Hash::check()` | Bandingkan password asli vs terenkripsi |
| `hasMany()` | Relasi "satu ke banyak" antar tabel |
| `belongsTo()` | Relasi "milik satu" antar tabel |
| `$fillable` | Daftar kolom yang boleh diisi massal |
| `->with('sukses', ...)` | Kirim pesan kilat ke halaman tujuan |
| `{{ }}` | Sintaks Blade untuk menampilkan data PHP dengan aman html-encoded |
| `@csrf` | Tag wajib di form untuk pengamanan & pencegahan pemalsuan pengiriman |
| `@extends` & `@yield` | Konsep membuat template layout induk & kerangka anak |
| `php artisan migrate` | Jalankan blueprint membikin tabel database MySQL dari kode |
| `php artisan storage:link` | Buat shortcut folder file supaya upload gambar dapat diakses di web |
| `$request->file(...)->store(...)` | Simpan sebuah tipe file gambar/dokumen yang di-upload ke Storage sistem |

---

## Latihan Mandiri 🎯

Setelah membaca modul ini, coba kerjakan tantangan berikut untuk menguji pemahamanmu:

1. **[Mudah]** Buka `routes/web.php`. Berapa total rute yang ada? Rute mana saja yang tidak memerlukan login?
2. **[Mudah]** Buka `AuthController.php`. Temukan method `logout()`. Apa yang dilakukannya terhadap session?
3. **[Sedang]** Buka `GalleryFoto.php`. Berapa relasi yang didefinisikan? Apa bedanya `belongsTo` dan `hasMany`?
4. **[Sedang]** Buka `resources/views/dashboard.blade.php`. Temukan kode `@foreach`. Apa fungsinya? Apa yang terjadi jika `$fotos` kosong?
5. **[Sedang]** Buka folder `database/migrations/`. Cari file migration untuk tabel `gallery_komentar_foto`. Tanggal berapa ia dibuat (lihat awalan nama filenya)? Dan field kolom apa saja yang disiapkan?
6. **[Tantangan]** Coba telusuri alur lengkap fitur **upload foto**: mulai dari form di Blade, kewajiban menyisipkan `@csrf`, Routes menerima POST, Middleware mengecek tiket login, Controller memindah file via File System di Storage & merekam di Model DB, hingga View sukses reload kembali. Tulis rute perjalanannya di buku tulismu!

---

*Modul ini dibuat untuk mendampingi proyek UKK (Uji Kompetensi Keahlian) SMKN 11 Malang.*
*Dikembangkan oleh: **Pandu Setya Wijaya** · XII RPL 2 · © 2026*
