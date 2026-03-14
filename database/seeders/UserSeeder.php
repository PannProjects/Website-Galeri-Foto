<?php

namespace Database\Seeders;

use App\Models\GalleryUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * UserSeeder — Mengisi data awal akun pengguna ke dalam tabel gallery_user
 * Data sesuai dengan akun pengembang proyek
 */
class UserSeeder extends Seeder
{
    /**
     * Jalankan seeder untuk membuat akun default
     */
    public function run(): void
    {
        // Buat akun pengguna utama (jika belum ada)
        GalleryUser::firstOrCreate(
            ['Username' => 'PannProjects'], // Kondisi pengecekan (tidak duplikat)
            [
                'NamaLengkap' => 'Pandu Setya',
                'Username'    => 'PannProjects',
                'Email'       => 'pandumalang321@gmail.com',
                'Password'    => Hash::make('pandusetya'),       // Enkripsi password
                'Alamat'      => 'Jl. Pelabuhan Ketapang 1',
            ]
        );

        $this->command->info('✅ Seeder berhasil: Akun "PannProjects" sudah dibuat.');
    }
}
