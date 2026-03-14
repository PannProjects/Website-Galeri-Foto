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
Route::get('/', function () {
    // Cek apakah user sudah punya 'tiket masuk' (session user_id)
    if (session('user_id')) {
        return redirect()->route('dashboard'); // Sudah login → ke dashboard
    }
    return redirect()->route('login'); // Belum login → ke halaman login
});
```

**Penjelasan ①:** Ibarat sebelum memanggil tukang masak, kita perlu tahu dulu tukang masak mana yang kita maksud. `use App\Http\Controllers\AuthController` artinya: *"Saya akan pakai AuthController dari folder app/Http/Controllers"*.

**Penjelasan ②:** `Route::get('/', ...)` artinya: *"Kalau ada orang yang membuka halaman `/` dengan metode GET (buka biasa lewat browser), jalankan kode di dalam kurung kurawal ini"*.

---

```php
// ③ Rute publik — siapa saja boleh akses, tidak perlu login
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
        $user = GalleryUser::where('Username', $request->username)->first();

        // ⑧ Periksa: user ada? dan password cocok?
        if (!$user || !Hash::check($request->password, $user->Password)) {
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
$user->albums  // ← Langsung dapat semua album! Tanpa SQL manual.
```

Di balik layar, Laravel otomatis menjalankan:
```sql
SELECT * FROM gallery_album WHERE UserID = [id user ini]
```

Kebalikannya adalah `belongsTo` — dipakai di Model `GalleryAlbum` untuk berkata *"Album ini DIMILIKI OLEH satu user"*.

---

## Bab 5 — Kesimpulan: Rangkuman Alur Lengkap

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

---

## Latihan Mandiri 🎯

Setelah membaca modul ini, coba kerjakan tantangan berikut untuk menguji pemahamanmu:

1. **[Mudah]** Buka `routes/web.php`. Berapa total rute yang ada? Rute mana saja yang tidak memerlukan login?
2. **[Mudah]** Buka `AuthController.php`. Temukan method `logout()`. Apa yang dilakukannya terhadap session?
3. **[Sedang]** Buka `GalleryFoto.php`. Berapa relasi yang didefinisikan? Apa bedanya `belongsTo` dan `hasMany`?
4. **[Sedang]** Buka `resources/views/dashboard.blade.php`. Temukan kode `@foreach`. Apa fungsinya? Apa yang terjadi jika `$fotos` kosong?
5. **[Tantangan]** Coba telusuri alur lengkap fitur **upload foto**: mulai dari tombol "Unggah" diklik di browser, melalui Routes → Middleware → Controller → Model → View. Tulis alurnya di buku tulismu!

---

*Modul ini dibuat untuk mendampingi proyek UKK (Uji Kompetensi Keahlian) SMKN 11 Malang.*
*Dikembangkan oleh: **Pandu Setya Wijaya** · XII RPL 2 · © 2026*
