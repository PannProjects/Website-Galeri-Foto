<?php

namespace App\Http\Controllers;

use App\Models\GalleryAlbum;
use App\Models\GalleryFoto;
use Illuminate\Http\Request;

/**
 * DashboardController — Menampilkan halaman utama galeri pengguna
 */
class DashboardController extends Controller
{
    /**
     * Tampilkan halaman dashboard dengan semua foto milik pengguna yang sedang login
     */
    public function index(Request $request)
    {
        $userId  = session('user_id');
        $albumId = $request->get('album'); // Filter berdasarkan album (opsional)

        // Ambil daftar album milik pengguna untuk filter dropdown
        $albums = GalleryAlbum::where('UserID', $userId)
            ->orderBy('NamaAlbum', 'asc')
            ->get();

        // Query foto: tampilkan semua milik user, dengan filter album jika ada
        $query = GalleryFoto::with(['album', 'likes', 'komentars'])
            ->where('UserID', $userId);

        if ($albumId) {
            $query->where('AlbumID', $albumId);
        }

        $fotos = $query->orderBy('TanggalUnggah', 'desc')->get();

        return view('dashboard', compact('fotos', 'albums', 'albumId'));
    }
}
