<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model GalleryLikeFoto
 * Merepresentasikan data like yang diberikan pengguna pada sebuah foto.
 * Tabel: gallery_likefoto
 */
class GalleryLikeFoto extends Model
{
    // Nama tabel
    protected $table = 'gallery_likefoto';

    // Primary key kustom
    protected $primaryKey = 'LikeID';

    // Kolom yang boleh diisi secara massal
    protected $fillable = [
        'FotoID',
        'UserID',
        'TanggalLike',
    ];

    // =========================================================================
    // RELASI ELOQUENT
    // =========================================================================

    /**
     * Like dimiliki (belongs to) oleh satu foto
     */
    public function foto()
    {
        return $this->belongsTo(GalleryFoto::class, 'FotoID', 'FotoID');
    }

    /**
     * Like dimiliki (belongs to) oleh satu pengguna
     */
    public function user()
    {
        return $this->belongsTo(GalleryUser::class, 'UserID', 'UserID');
    }
}
