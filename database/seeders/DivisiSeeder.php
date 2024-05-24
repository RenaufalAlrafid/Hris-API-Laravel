<?php

namespace Database\Seeders;

use App\Models\Divisi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DivisiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Divisi::truncate();
        Divisi::create([
            "name" => "IT"
        ]);

        Divisi::create([
            "name" => "HRD"
        ]);

        Divisi::create([
            "name" => "Finance"
        ]);

        Divisi::create([
            "name" => "Marketing"
        ]);

        Divisi::create([
            "name" => "Operational"
        ]);

        Divisi::create([
            "name" => "Production"
        ]);
    }
}
