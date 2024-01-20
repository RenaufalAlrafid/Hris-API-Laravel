<?php

namespace Database\Seeders;

use App\Models\Jabatan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Truncate tables
        Jabatan::truncate();

        // Enable foreign key checks after truncating
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        Jabatan::create([
            "name" => "Full Stack Developer",
            "divisi_id" => 1,
            "atasan" => false,
            "validator" => true,
        ]);
    }
}
