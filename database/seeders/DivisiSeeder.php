<?php

namespace Database\Seeders;

use App\Models\Divisi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DivisiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Truncate tables
        Divisi::truncate();

        // Enable foreign key checks after truncating
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        Divisi::create([
            "name" => "Informasi Dan Teknologi"
        ]);
        
    }
}
