<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model GalleryAlbum
 * Merepresentasikan album yang dikelola oleh pengguna.
 * Tabel: gallery_album
 */
class GalleryAlbum extends Model
{
    // Nama tabel
    protected $table = 'gallery_album';

    // Primary key kustom
    protected $primaryKey = 'AlbumID';

    // Kolom yang boleh diisi secara massal
    protected $fillable = [
        'NamaAlbum',
        'Deskripsi',
        'TanggalDibuat',
        'UserID',
    ];

    // =========================================================================
    // RELASI ELOQUENT
    // =========================================================================

    /**
     * Album dimiliki (belongs to) oleh satu pengguna
     */
    public function user()
    {
        return $this->belongsTo(GalleryUser::class, 'UserID', 'UserID');
    }

    /**
     * Satu album dapat memiliki banyak foto
     */
    public function fotos()
    {
        return $this->hasMany(GalleryFoto::class, 'AlbumID', 'AlbumID');
    }
}
