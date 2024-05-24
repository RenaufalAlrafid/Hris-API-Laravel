<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // User::create([
        //     "name" => "Manager IT",
        //     "email" => "menager@email.com",
        //     "password" => Hash::make("password"),
        //     "jabatan_id" => 1,
        //     "verification" => 1,
        //     "email_verified_at" => now()
        // ]);

        // make all user from jabatan


        $jabatan = [
            "Manager",
            "Ketua Staff",
            "Staff"
        ];

        $divisi = [
            "IT",
            "HRD",
            "Finance",
            "Marketing",
            "Operational",
            "Production"
        ];

        $faker = \Faker\Factory::create();



        foreach ($divisi as $key => $value) {
            foreach ($jabatan as $key2 => $value2) {
                $random_name = $faker->name;
                User::create([
                    "name" => $random_name,
                    "email" => strtolower(str_replace(" ", "", $value2 . $value)) . "@email.com",
                    "password" => Hash::make("hris1234"),
                    "jabatan_id" => $key * 3 + $key2 + 1,
                    "verification" => 1,
                    "email_verified_at" => now()
                ]);
            }
        }
    }
}
