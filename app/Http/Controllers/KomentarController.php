<?php

namespace App\Http\Controllers;

use App\Models\GalleryKomentarFoto;
use App\Models\GalleryFoto;
use Illuminate\Http\Request;

/**
 * KomentarController — Mengelola komentar pada foto
 * Mencakup: tambah komentar, hapus komentar
 */
class KomentarController extends Controller
{
    /**
     * Simpan komentar baru pada sebuah foto
     */
    public function store(Request $request, $fotoId)
    {
        // Pastikan foto ada
        $foto = GalleryFoto::findOrFail($fotoId);

        // Validasi dengan pesan Bahasa Indonesia
        $request->validate([
            'isi_komentar' => 'required|string|max:1000',
        ], [
            'isi_komentar.required' => 'Komentar tidak boleh kosong.',
            'isi_komentar.max'      => 'Komentar maksimal 1000 karakter.',
        ]);

        GalleryKomentarFoto::create([
            'FotoID'          => $foto->FotoID,
            'UserID'          => session('user_id'),
            'IsiKomentar'     => $request->isi_komentar,
            'TanggalKomentar' => now()->toDateString(),
        ]);

        return redirect()->route('foto.show', $fotoId)
            ->with('sukses', 'Komentar Anda berhasil ditambahkan.');
    }

    /**
     * Hapus komentar (hanya oleh pemilik komentar)
     */
    public function destroy($id)
    {
        $userId = session('user_id');

        // Pastikan komentar milik pengguna yang sedang login
        $komentar = GalleryKomentarFoto::where('KomentarID', $id)
            ->where('UserID', $userId)
            ->firstOrFail();

        $fotoId = $komentar->FotoID;
        $komentar->delete();

        return redirect()->route('foto.show', $fotoId)
            ->with('sukses', 'Komentar berhasil dihapus.');
    }
}
