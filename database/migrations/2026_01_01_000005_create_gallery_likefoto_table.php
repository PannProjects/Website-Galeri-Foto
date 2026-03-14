<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration untuk tabel gallery_likefoto
 * Menyimpan data like yang diberikan pengguna pada sebuah foto
 */
return new class extends Migration
{
    /**
     * Jalankan migration untuk membuat tabel gallery_likefoto
     */
    public function up(): void
    {
        Schema::create('gallery_likefoto', function (Blueprint $table) {
            $table->increments('LikeID');                              // Primary Key
            $table->unsignedInteger('FotoID');                         // Foreign Key → gallery_foto
            $table->unsignedInteger('UserID');                         // Foreign Key → gallery_user
            $table->date('TanggalLike');                               // Tanggal like diberikan
            $table->timestamps();

            // Satu pengguna hanya bisa like satu foto sekali
            $table->unique(['FotoID', 'UserID']);

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
     * Batalkan migration (rollback) — hapus tabel gallery_likefoto
     */
    public function down(): void
    {
        Schema::dropIfExists('gallery_likefoto');
    }
};
