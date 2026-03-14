<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration untuk tabel gallery_foto
 * Menyimpan data foto yang diunggah oleh pengguna ke dalam album
 */
return new class extends Migration
{
    /**
     * Jalankan migration untuk membuat tabel gallery_foto
     */
    public function up(): void
    {
        Schema::create('gallery_foto', function (Blueprint $table) {
            $table->increments('FotoID');                               // Primary Key
            $table->string('JudulFoto', 200);                          // Judul foto
            $table->text('DeskripsiFoto')->nullable();                  // Deskripsi foto
            $table->date('TanggalUnggah');                              // Tanggal foto diunggah
            $table->string('LokasiFile', 500);                         // Path/lokasi file di storage
            $table->unsignedInteger('AlbumID');                        // Foreign Key → gallery_album
            $table->unsignedInteger('UserID');                         // Foreign Key → gallery_user
            $table->timestamps();

            // Relasi ke tabel gallery_album
            $table->foreign('AlbumID')
                  ->references('AlbumID')
                  ->on('gallery_album')
                  ->onDelete('cascade');

            // Relasi ke tabel gallery_user
            $table->foreign('UserID')
                  ->references('UserID')
                  ->on('gallery_user')
                  ->onDelete('cascade');
        });
    }

    /**
     * Batalkan migration (rollback) — hapus tabel gallery_foto
     */
    public function down(): void
    {
        Schema::dropIfExists('gallery_foto');
    }
};
