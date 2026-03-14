<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model GalleryFoto
 * Merepresentasikan foto yang diunggah oleh pengguna ke dalam sebuah album.
 * Tabel: gallery_foto
 */
class GalleryFoto extends Model
{
    // Nama tabel
    protected $table = 'gallery_foto';

    // Primary key kustom
    protected $primaryKey = 'FotoID';

    // Kolom yang boleh diisi secara massal
    protected $fillable = [
        'JudulFoto',
        'DeskripsiFoto',
        'TanggalUnggah',
        'LokasiFile',
        'AlbumID',
        'UserID',
    ];

    // =========================================================================
    // RELASI ELOQUENT
    // =========================================================================

    /**
     * Foto dimiliki (belongs to) oleh satu pengguna
     */
    public function user()
    {
        return $this->belongsTo(GalleryUser::class, 'UserID', 'UserID');
    }

    /**
     * Foto berada (belongs to) dalam satu album
     */
    public function album()
    {
        return $this->belongsTo(GalleryAlbum::class, 'AlbumID', 'AlbumID');
    }

    /**
     * Satu foto dapat memiliki banyak komentar
     */
    public function komentars()
    {
        return $this->hasMany(GalleryKomentarFoto::class, 'FotoID', 'FotoID');
    }

    /**
     * Satu foto dapat memiliki banyak like
     */
    public function likes()
    {
        return $this->hasMany(GalleryLikeFoto::class, 'FotoID', 'FotoID');
    }
}
