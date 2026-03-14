<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model GalleryKomentarFoto
 * Merepresentasikan komentar yang diberikan pengguna pada sebuah foto.
 * Tabel: gallery_komentarfoto
 */
class GalleryKomentarFoto extends Model
{
    // Nama tabel
    protected $table = 'gallery_komentarfoto';

    // Primary key kustom
    protected $primaryKey = 'KomentarID';

    // Kolom yang boleh diisi secara massal
    protected $fillable = [
        'FotoID',
        'UserID',
        'IsiKomentar',
        'TanggalKomentar',
    ];

    // =========================================================================
    // RELASI ELOQUENT
    // =========================================================================

    /**
     * Komentar dimiliki (belongs to) oleh satu foto
     */
    public function foto()
    {
        return $this->belongsTo(GalleryFoto::class, 'FotoID', 'FotoID');
    }

    /**
     * Komentar dimiliki (belongs to) oleh satu pengguna
     */
    public function user()
    {
        return $this->belongsTo(GalleryUser::class, 'UserID', 'UserID');
    }
}
