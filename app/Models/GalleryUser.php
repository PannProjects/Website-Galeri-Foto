<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model GalleryUser
 * Merepresentasikan pengguna/akun pada aplikasi galeri foto.
 * Tabel: gallery_user
 */
class GalleryUser extends Model
{
    // Nama tabel yang digunakan (tidak mengikuti konvensi Laravel)
    protected $table = 'gallery_user';

    // Primary key kustom
    protected $primaryKey = 'UserID';

    // Kolom yang boleh diisi secara massal
    protected $fillable = [
        'Username',
        'Password',
        'Email',
        'NamaLengkap',
        'Alamat',
    ];

    // Sembunyikan kolom sensitif saat serialisasi
    protected $hidden = ['Password'];

    // =========================================================================
    // RELASI ELOQUENT
    // =========================================================================

    /**
     * Satu pengguna memiliki banyak album
     */
    public function albums()
    {
        return $this->hasMany(GalleryAlbum::class, 'UserID', 'UserID');
    }

    /**
     * Satu pengguna memiliki banyak foto
     */
    public function fotos()
    {
        return $this->hasMany(GalleryFoto::class, 'UserID', 'UserID');
    }

    /**
     * Satu pengguna memiliki banyak komentar
     */
    public function komentars()
    {
        return $this->hasMany(GalleryKomentarFoto::class, 'UserID', 'UserID');
    }

    /**
     * Satu pengguna memiliki banyak data like
     */
    public function likes()
    {
        return $this->hasMany(GalleryLikeFoto::class, 'UserID', 'UserID');
    }
}
