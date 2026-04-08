<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // Cek apakah akun admin sudah ada, jika belum baru insert
        $existing = DB::table('petugas')->where('nama', 'admin')->first();

        if (!$existing) {
            DB::table('petugas')->insert([
                'nama' => 'admin',
                'password' => 'admin123',
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            // Update role jika akun sudah ada tapi belum punya role admin
            DB::table('petugas')->where('nama', 'admin')->update([
                'role' => 'admin',
                'updated_at' => now(),
            ]);
        }
    }
}