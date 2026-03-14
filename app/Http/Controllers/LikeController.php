<?php

namespace App\Http\Controllers;

use App\Models\GalleryLikeFoto;
use App\Models\GalleryFoto;
use Illuminate\Http\Request;

/**
 * LikeController — Mengelola fitur like/unlike pada foto
 */
class LikeController extends Controller
{
    /**
     * Toggle like: jika sudah like → unlike, jika belum → like
     */
    public function toggle(Request $request, $fotoId)
    {
        // Pastikan foto ada
        $foto = GalleryFoto::findOrFail($fotoId);
        $userId = session('user_id');

        // Cek apakah sudah memberi like
        $like = GalleryLikeFoto::where('FotoID', $foto->FotoID)
            ->where('UserID', $userId)
            ->first();

        if ($like) {
            // Jika sudah like → hapus (unlike)
            $like->delete();
            $pesan = 'Like berhasil dihapus.';
        } else {
            // Jika belum like → tambahkan
            GalleryLikeFoto::create([
                'FotoID'     => $foto->FotoID,
                'UserID'     => $userId,
                'TanggalLike' => now()->toDateString(),
            ]);
            $pesan = 'Foto berhasil disukai!';
        }

        return redirect()->route('foto.show', $fotoId)->with('sukses', $pesan);
    }
}
