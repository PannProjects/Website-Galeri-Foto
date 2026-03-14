<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * DatabaseSeeder — Entry point untuk semua seeder aplikasi
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Jalankan semua seeder yang dibutuhkan oleh aplikasi
     */
    public function run(): void
    {
        // Seed data akun pengguna default
        $this->call([
            UserSeeder::class,
        ]);
    }
}
