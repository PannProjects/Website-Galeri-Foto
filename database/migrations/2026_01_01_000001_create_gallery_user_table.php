<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration untuk tabel gallery_user
 * Menyimpan data akun pengguna aplikasi galeri foto
 */
return new class extends Migration
{
    /**
     * Jalankan migration untuk membuat tabel gallery_user
     */
    public function up(): void
    {
        Schema::create('gallery_user', function (Blueprint $table) {
            $table->increments('UserID');                           // Primary Key
            $table->string('Username', 100)->unique();             // Username unik
            $table->string('Password', 255);                       // Password (bcrypt)
            $table->string('Email', 150)->unique();                // Email unik
            $table->string('NamaLengkap', 200);                    // Nama lengkap pengguna
            $table->text('Alamat')->nullable();                    // Alamat (opsional)
            $table->timestamps();                                  // created_at, updated_at
        });
    }

    /**
     * Batalkan migration (rollback) — hapus tabel gallery_user
     */
    public function down(): void
    {
        Schema::dropIfExists('gallery_user');
    }
};
