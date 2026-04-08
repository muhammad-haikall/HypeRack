<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // Menghapus data lama agar tidak duplikat saat dijalankan ulang
        DB::table('petugas')->truncate();

        DB::table('petugas')->insert([
            'nama' => 'admin',
            'password' => 'admin123', // Password disimpan apa adanya (Plain Text)
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}