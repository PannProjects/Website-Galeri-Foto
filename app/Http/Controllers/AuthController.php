<?php

namespace App\Http\Controllers;

use App\Models\GalleryUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * AuthController — Mengelola autentikasi pengguna secara manual (tanpa Breeze/Jetstream)
 * Mencakup: tampil form login, proses login, tampil form registrasi, proses registrasi, logout
 */
class AuthController extends Controller
{
    // =========================================================================
    // LOGIN
    // =========================================================================

    /**
     * Tampilkan halaman form login
     */
    public function showLogin()
    {
        // Jika sudah login, langsung redirect ke dashboard
        if (session('user_id')) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    /**
     * Proses data form login yang dikirim pengguna
     */
    public function login(Request $request)
    {
        // Validasi input dengan pesan Bahasa Indonesia
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        // Cari pengguna berdasarkan username
        $user = GalleryUser::where('Username', $request->username)->first();

        // Periksa apakah pengguna ditemukan dan password cocok
        if (!$user || !Hash::check($request->password, $user->Password)) {
            return back()
                ->withInput(['username' => $request->username])
                ->withErrors(['login' => 'Username atau password yang Anda masukkan salah.']);
        }

        // Simpan data sesi pengguna
        $request->session()->put('user_id', $user->UserID);
        $request->session()->put('user_nama', $user->NamaLengkap);
        $request->session()->put('user_username', $user->Username);

        // Perbarui sesi untuk keamanan (session regeneration)
        $request->session()->regenerate();

        return redirect()->route('dashboard')
            ->with('sukses', 'Selamat datang kembali, ' . $user->NamaLengkap . '!');
    }

    // =========================================================================
    // REGISTRASI
    // =========================================================================

    /**
     * Tampilkan halaman form registrasi pengguna baru
     */
    public function showRegister()
    {
        // Jika sudah login, langsung redirect ke dashboard
        if (session('user_id')) {
            return redirect()->route('dashboard');
        }
        return view('auth.register');
    }

    /**
     * Proses data form registrasi untuk membuat akun baru
     */
    public function register(Request $request)
    {
        // Validasi input dengan pesan Bahasa Indonesia
        $request->validate([
            'username'     => 'required|string|min:3|max:100|unique:gallery_user,Username',
            'email'        => 'required|email|max:150|unique:gallery_user,Email',
            'password'     => 'required|string|min:6|confirmed',
            'nama_lengkap' => 'required|string|max:200',
            'alamat'       => 'nullable|string',
        ], [
            'username.required'      => 'Username wajib diisi.',
            'username.min'           => 'Username minimal 3 karakter.',
            'username.max'           => 'Username maksimal 100 karakter.',
            'username.unique'        => 'Username tersebut sudah digunakan, coba yang lain.',
            'email.required'         => 'Email wajib diisi.',
            'email.email'            => 'Format email tidak valid.',
            'email.unique'           => 'Email tersebut sudah terdaftar.',
            'password.required'      => 'Password wajib diisi.',
            'password.min'           => 'Password minimal 6 karakter.',
            'password.confirmed'     => 'Konfirmasi password tidak cocok.',
            'nama_lengkap.required'  => 'Nama lengkap wajib diisi.',
            'nama_lengkap.max'       => 'Nama lengkap maksimal 200 karakter.',
        ]);

        // Buat akun pengguna baru
        $user = GalleryUser::create([
            'Username'    => $request->username,
            'Password'    => Hash::make($request->password), // Enkripsi password
            'Email'       => $request->email,
            'NamaLengkap' => $request->nama_lengkap,
            'Alamat'      => $request->alamat,
        ]);

        // Langsung login setelah registrasi berhasil
        $request->session()->put('user_id', $user->UserID);
        $request->session()->put('user_nama', $user->NamaLengkap);
        $request->session()->put('user_username', $user->Username);
        $request->session()->regenerate();

        return redirect()->route('dashboard')
            ->with('sukses', 'Akun Anda berhasil dibuat! Selamat bergabung, ' . $user->NamaLengkap . '!');
    }

    // =========================================================================
    // LOGOUT
    // =========================================================================

    /**
     * Proses logout — hapus semua data sesi pengguna
     */
    public function logout(Request $request)
    {
        // Hapus semua data sesi
        $request->session()->flush();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('sukses', 'Anda telah berhasil keluar dari aplikasi.');
    }
}
