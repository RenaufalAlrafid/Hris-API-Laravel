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

        User::create([
            "name" => "Manager IT",
            "email" => "menager@email.com",
            "password" => Hash::make("password"),
            "jabatan_id" => 1,
            "verification" => 1
        ]);

        User::create([
            "name" => "Ketua Staff IT",
            "email" => "ketua@email.com",
            "password" => Hash::make("password"),
            "jabatan_id" => 2,
            "verification" => 1
        ]);

        User::create([
            "name" => "Staff IT",
            "email" => "staff@email.com",
            "password" => Hash::make("password"),
            "jabatan_id" => 3,
        "verification" => 1
        ]);
    }
}
