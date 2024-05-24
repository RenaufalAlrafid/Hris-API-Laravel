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
        $divisi = [
            "IT",
            "HRD",
            "Finance",
            "Marketing",
            "Operational",
            "Production"
        ];
        $jabatan = [
            "Manager",
            "Ketua Staff",
            "Staff"
        ];

        foreach ($divisi as $key => $value) {
            foreach ($jabatan as $key2 => $value2) {
                // if divisi "HRD" validator = 1
                // if jabatan "Manager" atasan = 1
                
                $validator = 0;
                $atasan = 0;
                if ($value == "HRD") {
                    $validator = 1;
                }
                if ($value2 == "Manager") {
                    $atasan = 1;
                }

                Jabatan::create([
                    "name" => $value2 . " " . $value,
                    "divisi_id" => $key + 1,
                    "atasan" => $atasan,
                    "validator" => $validator
                ]);
            }
        }


        // Jabatan::create([
        //     "name" => "Ketua Staff IT",
        //     "divisi_id" => 1,
        //     "atasan" => 0,
        //     "validator" => 1
        // ]);
        // Jabatan::create([
        //     "name" => "Staff IT",
        //     "divisi_id" => 1,
        //     "atasan" => 0,
        //     "validator" => 0
        // ]);
    }
}
