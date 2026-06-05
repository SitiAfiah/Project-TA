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
        DB::table('kolat')->insert([
            'id' => 1, // Kita paksa ID 1 biar nyambung sama UserSeeder tadi
            'nama_kolat' => 'Kolat Unej',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
