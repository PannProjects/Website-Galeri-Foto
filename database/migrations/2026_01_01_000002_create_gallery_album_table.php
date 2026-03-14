<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration untuk tabel gallery_album
 * Menyimpan data album yang dibuat oleh pengguna
 */
return new class extends Migration
{
    /**
     * Jalankan migration untuk membuat tabel gallery_album
     */
    public function up(): void
    {
        Schema::create('gallery_album', function (Blueprint $table) {
            $table->increments('AlbumID');                              // Primary Key
            $table->string('NamaAlbum', 200);                          // Nama album
            $table->text('Deskripsi')->nullable();                      // Deskripsi album
            $table->date('TanggalDibuat');                              // Tanggal album dibuat
            $table->unsignedInteger('UserID');                          // Foreign Key → gallery_user
            $table->timestamps();

            // Relasi ke tabel gallery_user
            $table->foreign('UserID')
                  ->references('UserID')
                  ->on('gallery_user')
                  ->onDelete('cascade');
        });
    }

    /**
     * Batalkan migration (rollback) — hapus tabel gallery_album
     */
    public function down(): void
    {
        Schema::dropIfExists('gallery_album');
    }
};
