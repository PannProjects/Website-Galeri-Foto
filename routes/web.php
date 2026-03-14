<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AlbumController;
use App\Http\Controllers\FotoController;
use App\Http\Controllers\KomentarController;
use App\Http\Controllers\LikeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rute Web Aplikasi Galeri Foto
|--------------------------------------------------------------------------
| Rute Publik: Login & Registrasi (tidak perlu autentikasi)
| Rute Terproteksi: Semua fitur galeri (wajib login via middleware auth.galeri)
*/

// ============================================================
// RUTE REDIRECT UTAMA
// ============================================================

// Redirect ke dashboard jika sudah login, ke login jika belum
Route::get('/', function () {
    if (session('user_id')) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

// ============================================================
// RUTE PUBLIK — Autentikasi (Login & Registrasi)
// ============================================================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ============================================================
// RUTE TERPROTEKSI — Membutuhkan Login (Middleware: auth.galeri)
// ============================================================
Route::middleware('auth.galeri')->group(function () {

    // --- Dashboard Utama ---
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- Manajemen Album ---
    Route::get('/album', [AlbumController::class, 'index'])->name('album.index');
    Route::get('/album/buat', [AlbumController::class, 'create'])->name('album.create');
    Route::post('/album', [AlbumController::class, 'store'])->name('album.store');
    Route::delete('/album/{id}', [AlbumController::class, 'destroy'])->name('album.destroy');

    // --- Manajemen Foto ---
    Route::get('/foto/unggah', [FotoController::class, 'create'])->name('foto.create');
    Route::post('/foto', [FotoController::class, 'store'])->name('foto.store');
    Route::get('/foto/{id}', [FotoController::class, 'show'])->name('foto.show');
    Route::delete('/foto/{id}', [FotoController::class, 'destroy'])->name('foto.destroy');

    // --- Komentar ---
    Route::post('/foto/{fotoId}/komentar', [KomentarController::class, 'store'])->name('komentar.store');
    Route::delete('/komentar/{id}', [KomentarController::class, 'destroy'])->name('komentar.destroy');

    // --- Like / Unlike ---
    Route::post('/foto/{fotoId}/like', [LikeController::class, 'toggle'])->name('like.toggle');
});
