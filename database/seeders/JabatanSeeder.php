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
        Jabatan::create([
            "name" => "Full Stack Developer",
            "divisi_id" => 1,
            "atasan" => false,
            "validator" => true,
        ]);
    }
}
