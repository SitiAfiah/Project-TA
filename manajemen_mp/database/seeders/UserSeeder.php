<?php

namespace Database\Seeders;

use App\Models\Anggota;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ambil ID Role Pelatih
        $rolePelatih = Role::where('nama_role', 'Pelatih')->first();
        $rolePengurus = Role::where('nama_role', 'Pengurus')->first();

        // 2. Buat Akun User untuk Pelatih
        $user = User::create([
            'email'     => 'pelatih@tapakmp.com',
            'password'  => Hash::make('password123'), // Password default
        ]);

        // 3. Buat Profil Anggota untuk User tersebut
        $anggota = Anggota::create([
            'user_id'       => $user->id,
            'no_induk'      => 'MP-2026-001',
            'nama_lengkap'  => 'Siti Afiah Dahliawati',
            'jenis_kelamin' => 'P',
            'tempat_lahir'  => 'Jember',
            'tgl_lahir'     => '2004-10-29',
            'no_hp'         => '081455057453',
            'kolat_id'      => 1, // Menyambung ke Kolat Unej di KolatSeeder
            'tingkatan'     => 'Balik 1',
            'tgl_gabung'    => now(),
            'status'        => 'Aktif',
            'alamat'        => 'Jl. Merpati Putih No. 1, Jember',
            'jabatan'       => 'Pelatih, Pengurus Kolat',
        ]);

        // 4. INI BAGIAN TERPENTING: Masukkan data ke tabel pivot anggota_role!
        // Perintah attach() ini akan otomatis mengisi tabel anggota_role dengan anggota_id dan role_id
        if ($rolePelatih) {
            $anggota->roles()->attach($rolePelatih->id);
        }

        // Menempelkan peran sebagai Pengurus (Menjadikannya akun super ganda untuk kebutuhan pengetesan)
        if ($rolePengurus) {
            $anggota->roles()->attach($rolePengurus->id);
        }

    }
}
