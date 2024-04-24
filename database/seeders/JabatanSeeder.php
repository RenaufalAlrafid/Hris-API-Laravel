<?php

namespace Database\Seeders;

use App\Models\Jabatan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Jabatan::truncate();
        Jabatan::create([
            "name" => "Manager IT",
            "divisi_id" => 1,
            "atasan" => 1,
            "validator" => 1
        ]);
        Jabatan::create([
            "name" => "Ketua Staff IT",
            "divisi_id" => 1,
            "atasan" => 0,
            "validator" => 1
        ]);
        Jabatan::create([
            "name" => "Staff IT",
            "divisi_id" => 1,
            "atasan" => 0,
            "validator" => 0
        ]);
    }
}
