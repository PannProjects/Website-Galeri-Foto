<?php

namespace App\Http\Controllers;

use App\Models\GalleryAlbum;
use App\Models\GalleryFoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * FotoController — Mengelola foto dalam galeri pengguna
 * Mencakup: tampil semua foto, form upload, proses upload, detail foto, hapus foto
 */
class FotoController extends Controller
{
    /**
     * Tampilkan form upload foto baru
     */
    public function create()
    {
        $userId = session('user_id');

        // Ambil daftar album milik pengguna sebagai pilihan
        $albums = GalleryAlbum::where('UserID', $userId)
            ->orderBy('NamaAlbum', 'asc')
            ->get();

        // Jika belum punya album, arahkan ke halaman buat album dulu
        if ($albums->isEmpty()) {
            return redirect()->route('album.create')
                ->with('peringatan', 'Anda belum memiliki album. Buat album terlebih dahulu sebelum mengunggah foto.');
        }

        return view('foto.create', compact('albums'));
    }

    /**
     * Proses upload foto baru ke storage
     */
    public function store(Request $request)
    {
        // Validasi input dengan pesan Bahasa Indonesia
        $request->validate([
            'judul_foto'   => 'required|string|max:200',
            'deskripsi'    => 'nullable|string',
            'album_id'     => 'required|integer|exists:gallery_album,AlbumID',
            'file_foto'    => 'required|file|mimes:jpg,jpeg,png,webp|max:5120',
        ], [
            'judul_foto.required'  => 'Judul foto wajib diisi.',
            'judul_foto.max'       => 'Judul foto maksimal 200 karakter.',
            'album_id.required'    => 'Pilih album untuk foto ini.',
            'album_id.exists'      => 'Album yang dipilih tidak valid.',
            'file_foto.required'   => 'File foto wajib diunggah.',
            'file_foto.mimes'      => 'Format file tidak didukung. Gunakan JPG, JPEG, PNG, atau WEBP.',
            'file_foto.max'        => 'Ukuran file foto maksimal 5 MB.',
        ]);

        // Pastikan album milik pengguna yang login
        $album = GalleryAlbum::where('AlbumID', $request->album_id)
            ->where('UserID', session('user_id'))
            ->firstOrFail();

        // Simpan file ke storage/app/public/fotos/
        $path = $request->file('file_foto')->store('fotos', 'public');

        // Simpan data foto ke database
        GalleryFoto::create([
            'JudulFoto'     => $request->judul_foto,
            'DeskripsiFoto' => $request->deskripsi,
            'TanggalUnggah' => now()->toDateString(),
            'LokasiFile'    => $path,
            'AlbumID'       => $album->AlbumID,
            'UserID'        => session('user_id'),
        ]);

        return redirect()->route('dashboard')
            ->with('sukses', 'Foto "' . $request->judul_foto . '" berhasil diunggah ke album "' . $album->NamaAlbum . '"!');
    }

    /**
     * Tampilkan halaman detail foto beserta komentar dan like
     */
    public function show($id)
    {
        // Ambil foto beserta relasi yang dibutuhkan
        $foto = GalleryFoto::with([
            'user',
            'album',
            'komentars.user',
            'likes',
        ])->findOrFail($id);

        $userId = session('user_id');

        // Periksa apakah pengguna yang login sudah memberi like
        $sudahLike = $foto->likes->contains('UserID', $userId);

        return view('foto.show', compact('foto', 'sudahLike', 'userId'));
    }

    /**
     * Hapus foto dari database dan storage
     */
    public function destroy($id)
    {
        $userId = session('user_id');

        // Pastikan foto milik pengguna yang sedang login
        $foto = GalleryFoto::where('FotoID', $id)
            ->where('UserID', $userId)
            ->firstOrFail();

        $judulFoto = $foto->JudulFoto;

        // Hapus file fisik dari storage
        if (Storage::disk('public')->exists($foto->LokasiFile)) {
            Storage::disk('public')->delete($foto->LokasiFile);
        }

        // Hapus data dari database (cascade akan hapus komentar & like)
        $foto->delete();

        return redirect()->route('dashboard')
            ->with('sukses', 'Foto "' . $judulFoto . '" berhasil dihapus.');
    }
}
