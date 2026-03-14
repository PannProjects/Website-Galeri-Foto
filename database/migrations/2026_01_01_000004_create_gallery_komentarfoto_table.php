<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration untuk tabel gallery_komentarfoto
 * Menyimpan komentar yang diberikan pengguna pada sebuah foto
 */
return new class extends Migration
{
    /**
     * Jalankan migration untuk membuat tabel gallery_komentarfoto
     */
    public function up(): void
    {
        Schema::create('gallery_komentarfoto', function (Blueprint $table) {
            $table->increments('KomentarID');                          // Primary Key
            $table->unsignedInteger('FotoID');                         // Foreign Key → gallery_foto
            $table->unsignedInteger('UserID');                         // Foreign Key → gallery_user
            $table->text('IsiKomentar');                               // Isi teks komentar
            $table->date('TanggalKomentar');                           // Tanggal komentar dibuat
            $table->timestamps();

            // Relasi ke tabel gallery_foto
            $table->foreign('FotoID')
                  ->references('FotoID')
                  ->on('gallery_foto')
                  ->onDelete('cascade');

            // Relasi ke tabel gallery_user
            $table->foreign('UserID')
                  ->references('UserID')
                  ->on('gallery_user')
                  ->onDelete('cascade');
        });
    }

    /**
     * Batalkan migration (rollback) — hapus tabel gallery_komentarfoto
     */
    public function down(): void
    {
        Schema::dropIfExists('gallery_komentarfoto');
    }
};
