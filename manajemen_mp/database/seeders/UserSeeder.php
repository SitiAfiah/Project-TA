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
        $rolePelatih = Role::where('nama_role', 'Pelatih')->first();

        // 2. Buat Akun User untuk Pelatih
        $user = User::create([
            'role_id'  => $rolePelatih->id,
            'email'     => 'pelatih@tapakmp.com',
            'password'  => Hash::make('password123'), // Password default
        ]);

        // 3. Buat Profil Anggota untuk User tersebut
        Anggota::create([
            'user_id'       => $user->id,
            'role_id'       => $rolePelatih->id,
            'no_induk'      => 'MP-2026-001',
            'nama_lengkap'  => 'Siti Afiah Dahliawati',
            'jenis_kelamin' => 'P',
            'tempat_lahir'  => 'Jember',
            'tgl_lahir'     => '2004-10-29',
            'no_hp'         => '081455057453',
            'kolat_id'      => 1, // Pastikan sudah ada data Kolat ID 1 atau sesuaikan
            'tingkatan'     => 'Balik 1',
            'tgl_gabung'    => now(),
            'status'        => 'Aktif',
            'alamat'        => 'Jl. Merpati Putih No. 1, Jember',
            'jabatan'       => 'Pelatih Kolat',
        ]);

    }
}
