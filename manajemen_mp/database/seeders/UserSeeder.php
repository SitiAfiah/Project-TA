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
        $rolePengurus = Role::where('nama_role', 'Pengurus')->first();
        $rolePelatih  = Role::where('nama_role', 'Pelatih')->first();
        $roleAnggota  = Role::where('nama_role', 'Anggota')->first();

        // ==========================================================
        // AKUN 1: PENGURUS (Akun kamu: Super Admin = Pelatih + Pengurus)
        // ==========================================================
        $userPengurus = User::create([
            'email'    => 'pengurus@tapakmp.com',
            'password' => Hash::make('password123'),
        ]);

        $anggotaPengurus = Anggota::create([
            'user_id'       => $userPengurus->id,
            'no_induk'      => 'MP-2026-001',
            'nama_lengkap'  => 'Siti Afiah Dahliawati',
            'jenis_kelamin' => 'P',
            'tempat_lahir'  => 'Jember',
            'tgl_lahir'     => '2004-10-29',
            'no_hp'         => '081455057453',
            'kolat_id'      => 1, // Pastikan ada data Kolat dengan ID 1
            'tingkatan'     => 'Balik 1',
            'tgl_gabung'    => now(),
            'status'        => 'Aktif',
            'alamat'        => 'Jl. Merpati Putih No. 1, Jember',
            'jabatan'       => 'Pengurus (Ketua)',
        ]);

        // Tempelkan HANYA peran Pengurus
        if ($rolePengurus) {
            $anggotaPengurus->roles()->attach($rolePengurus->id);
        }
        // ==========================================================
        // AKUN 2: PELATIH (Hanya bisa akses menu kepelatihan)
        // ==========================================================
        $userPelatih = User::create([
            'email'    => 'pelatih@tapakmp.com',
            'password' => Hash::make('password123'),
        ]);

        $anggotaPelatih = Anggota::create([
            'user_id'       => $userPelatih->id,
            'no_induk'      => 'MP-2026-002',
            'nama_lengkap'  => 'Budi Santoso',
            'jenis_kelamin' => 'L',
            'tempat_lahir'  => 'Banyuwangi',
            'tgl_lahir'     => '2000-05-15',
            'no_hp'         => '081234567890',
            'kolat_id'      => 1,
            'tingkatan'     => 'Kombinasi 1',
            'tgl_gabung'    => now()->subYears(2),
            'status'        => 'Aktif',
            'alamat'        => 'Jl. Mastrip, Jember',
            'jabatan'       => 'Pelatih',
        ]);

        // Tempelkan peran Pelatih
        if ($rolePelatih) {
            $anggotaPelatih->roles()->attach($rolePelatih->id);
        }

        // Hubungkan pelatih dengan Kolat (1 = Unej)
        $anggotaPelatih->kolatLatihan()->attach([1]); 

        // ==========================================================
        // AKUN 3: ANGGOTA (Hanya bisa akses dashboard anggota, presensi, spp)
        // ==========================================================
        $userAnggota = User::create([
            'email'    => 'anggota@tapakmp.com',
            'password' => Hash::make('password123'),
        ]);

        $anggotaBiasa = Anggota::create([
            'user_id'       => $userAnggota->id,
            'no_induk'      => 'MP-2026-003',
            'nama_lengkap'  => 'Siti Nurul Amalia',
            'jenis_kelamin' => 'P',
            'tempat_lahir'  => 'Jember',
            'tgl_lahir'     => '2005-08-20',
            'no_hp'         => '085712345678',
            'kolat_id'      => 1,
            'tingkatan'     => 'Dasar 1',
            'tgl_gabung'    => now()->subMonths(3),
            'status'        => 'Aktif',
            'alamat'        => 'Jl. Kalimantan, Jember',
            'jabatan'       => 'Anggota',
        ]);

        // Tempelkan hanya peran Anggota
        if ($roleAnggota) {
            $anggotaBiasa->roles()->attach($roleAnggota->id);
        }

    }
}
