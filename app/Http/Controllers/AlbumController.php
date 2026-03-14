<?php

namespace App\Http\Controllers;

use App\Models\GalleryAlbum;
use Illuminate\Http\Request;

/**
 * AlbumController — Mengelola album foto pengguna
 * Mencakup: daftar album, form buat album, simpan album, hapus album
 */
class AlbumController extends Controller
{
    /**
     * Tampilkan daftar semua album milik pengguna yang sedang login
     */
    public function index()
    {
        $userId = session('user_id');

        $albums = GalleryAlbum::withCount('fotos')
            ->where('UserID', $userId)
            ->orderBy('TanggalDibuat', 'desc')
            ->get();

        return view('album.index', compact('albums'));
    }

    /**
     * Tampilkan form untuk membuat album baru
     */
    public function create()
    {
        return view('album.create');
    }

    /**
     * Simpan album baru ke database
     */
    public function store(Request $request)
    {
        // Validasi input dengan pesan Bahasa Indonesia
        $request->validate([
            'nama_album' => 'required|string|max:200',
            'deskripsi'  => 'nullable|string',
        ], [
            'nama_album.required' => 'Nama album wajib diisi.',
            'nama_album.max'      => 'Nama album maksimal 200 karakter.',
        ]);

        GalleryAlbum::create([
            'NamaAlbum'     => $request->nama_album,
            'Deskripsi'     => $request->deskripsi,
            'TanggalDibuat' => now()->toDateString(),
            'UserID'        => session('user_id'),
        ]);

        return redirect()->route('album.index')
            ->with('sukses', 'Album "' . $request->nama_album . '" berhasil dibuat!');
    }

    /**
     * Hapus album beserta semua foto di dalamnya
     */
    public function destroy($id)
    {
        $userId = session('user_id');

        // Pastikan album milik pengguna yang sedang login
        $album = GalleryAlbum::where('AlbumID', $id)
            ->where('UserID', $userId)
            ->firstOrFail();

        $namaAlbum = $album->NamaAlbum;
        $album->delete(); // Cascade akan menghapus foto di dalamnya

        return redirect()->route('album.index')
            ->with('sukses', 'Album "' . $namaAlbum . '" beserta semua fotonya berhasil dihapus.');
    }
}
