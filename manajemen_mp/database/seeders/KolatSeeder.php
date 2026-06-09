<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KolatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       // Menghapus data lama agar tidak bentrok saat migrate:fresh --seed
        DB::table('kolat')->delete();

        DB::table('kolat')->insert([
            [
                'id'         => 1, // Wajib ID 1 agar nyambung dengan UserSeeder (Siti, Budi, Andi)
                'nama_kolat' => 'Kolat Unej',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => 2,
                'nama_kolat' => 'Kolat Matasa',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => 3,
                'nama_kolat' => 'Kolat Ajung',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    
    }
}
