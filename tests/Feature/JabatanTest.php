<?php

namespace Tests\Feature;

use App\Models\Divisi;
use App\Models\Jabatan;
use App\Models\User;
use Database\Seeders\DivisiSeeder;
use Database\Seeders\JabatanSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;
use Illuminate\Support\Str;

class JabatanTest extends TestCase
{
    function JabatanSeedingTest() {
        $this->seed([UserSeeder::class]);
        $this->seed([DivisiSeeder::class]);
        $this->seed([JabatanSeeder::class]);
    }
    
    public function testJabatanGetSuccess() {
        $this->JabatanSeedingTest();
        $username = fake()->name();
        $email = fake()->email();
        $token = Str::uuid()->toString();
        User::create([
            "username" => $username,
            "email" => $email,
            'password' => Hash::make('coba123'),
            'jabatan_id' => 1,
            'validation' => true,
            'token' => $token,
        ]);
        $divisi = Divisi::create([
            "name" => "divisi",
        ]);

        for ($i=0; $i < 15; $i++) { 
            Jabatan::create([
                "name" => fake()->jobTitle(),
                "divisi_id" => $divisi->id,
                "atasan" => false,
                "validator" => false,
            ]);
        }

        $all_jabatan = Jabatan::all()->count();

        $response = $this->get('/jabatan', [
            "Authorization" => $token
        ])->assertStatus(200)->json();
        Log::info(json_encode($response, JSON_PRETTY_PRINT));
        $this->assertEquals(10, count($response['data']));
        $this->assertEquals($all_jabatan, $response['meta']['total']);
    }

    public function testJabatanSearchByName() {
        $this->JabatanSeedingTest();
        $username = fake()->name();
        $email = fake()->email();
        $token = Str::uuid()->toString();
        User::create([
            "username" => $username,
            "email" => $email,
            'password' => Hash::make('coba123'),
            'jabatan_id' => 1,
            'validation' => true,
            'token' => $token,
        ]);
        $divisi = Divisi::create([
            "name" => "divisi",
        ]);

        for ($i=0; $i < 15; $i++) { 
            Jabatan::create([
                "name" => "test {$i}",
                "divisi_id" => $divisi->id,
                "atasan" => false,
                "validator" => false,
            ]);
        }
        $response = 
        $this->get('/jabatan?name=test', [
            "Authorization" => $token
        ])->assertStatus(200)->json();
        Log::info(json_encode($response, JSON_PRETTY_PRINT));
        $this->assertEquals(10, count($response['data']));
        $this->assertEquals(15, $response['meta']['total']);
    }

    public function testJabatanSearchByDivisiId() {
        $this->JabatanSeedingTest();
        $username = fake()->name();
        $email = fake()->email();
        $token = Str::uuid()->toString();
        User::create([
            "username" => $username,
            "email" => $email,
            'password' => Hash::make('coba123'),
            'jabatan_id' => 1,
            'validation' => true,
            'token' => $token,
        ]);
        $divisi = Divisi::create([
            "name" => "divisi",
        ]);

        for ($i=0; $i < 15; $i++) { 
            Jabatan::create([
                "name" => fake()->jobTitle(),
                "divisi_id" => $divisi->id,
                "atasan" => false,
                "validator" => false,
            ]);
        }
        $response = 
        $this->get('/jabatan?divisi_id=2', [
            "Authorization" => $token
        ])->assertStatus(200)->json();
        Log::info(json_encode($response, JSON_PRETTY_PRINT));
        $this->assertEquals(10, count($response['data']));
        $this->assertEquals(15, $response['meta']['total']);
    }

    public function testJabatanSearchByNameAndDivisiId() {
        $this->JabatanSeedingTest();
        $username = fake()->name();
        $email = fake()->email();
        $token = Str::uuid()->toString();
        User::create([
            "username" => $username,
            "email" => $email,
            'password' => Hash::make('coba123'),
            'jabatan_id' => 1,
            'validation' => true,
            'token' => $token,
        ]);
        $divisi = Divisi::create([
            "name" => "divisi",
        ]);

        for ($i=0; $i < 15; $i++) { 
            Jabatan::create([
                "name" => "test {$i}",
                "divisi_id" => $divisi->id,
                "atasan" => false,
                "validator" => false,
            ]);
        }
        $response = 
        $this->get('/jabatan?name=test&divisi_id=2', [
            "Authorization" => $token
        ])->assertStatus(200)->json();
        Log::info(json_encode($response, JSON_PRETTY_PRINT));
        $this->assertEquals(10, count($response['data']));
        $this->assertEquals(15, $response['meta']['total']);
    }

    public function testJabatanGetPaginate() {
        $this->JabatanSeedingTest();
        $username = fake()->name();
        $email = fake()->email();
        $token = Str::uuid()->toString();
        User::create([
            "username" => $username,
            "email" => $email,
            'password' => Hash::make('coba123'),
            'jabatan_id' => 1,
            'validation' => true,
            'token' => $token,
        ]);
        $divisi = Divisi::create([
            "name" => "divisi",
        ]);

        for ($i=0; $i < 15; $i++) { 
            Jabatan::create([
                "name" => fake()->jobTitle(),
                "divisi_id" => $divisi->id,
                "atasan" => false,
                "validator" => false,
            ]);
        }
        $jabatan = Jabatan::all()->count();
        $response = $this->get('/jabatan?page=2&size=5', [
            "Authorization" => $token
        ])->assertStatus(200)->json();
        Log::info(json_encode($response, JSON_PRETTY_PRINT));
        $this->assertEquals(5, count($response['data']));
        $this->assertEquals($jabatan, $response['meta']['total']);
        $this->assertEquals(2,$response['meta']['current_page']);
    }

    public function testJabatanStoreSuccess() {
        $this->JabatanSeedingTest();
        $username = fake()->name();
        $email = fake()->email();
        $token = Str::uuid()->toString();
        $user = User::create([
            "username" => $username,
            "email" => $email,
            'password' => Hash::make('coba123'),
            'jabatan_id' => 1,
            'validation' => true,
            'token' => $token,
        ]);
        $divisi = Divisi::create([
            "name" => "divisi",
        ]);
        $data = [
            "name" => "test",
            "divisi_id" => $divisi->id,
            "atasan" => false,
            "validator" => false,
        ];
        $response = $this->post('/jabatan', $data, [
            "Authorization" => $token
        ])->assertStatus(200)->assertJson([
            "data" => [
                "name" => "test",
                "divisi_id" => $divisi->id,
                "atasan" => false,
                "validator" => false,
            ]
        ]);

        

    }

    public function testJabatanStoreFailedJabatanAlreadyRegistered() {
        $this->JabatanSeedingTest();
        $username = fake()->name();
        $email = fake()->email();
        $token = Str::uuid()->toString();
        $user = User::create([
            "username" => $username,
            "email" => $email,
            'password' => Hash::make('coba123'),
            'jabatan_id' => 1,
            'validation' => true,
            'token' => $token,
        ]);
        $divisi = Divisi::create([
            "name" => "divisi",
        ]);
        $jabatan = Jabatan::create([
            "name" => "test",
            "divisi_id" => $divisi->id,
            "atasan" => false,
            "validator" => false,
        ]);
        $this->post('/jabatan', [
            "name" => $jabatan->name,
            "divisi_id" => $jabatan->divisi_id,
            "atasan" => false,
            "validator" => false,
        ], [
            "Authorization" => $token
        ])->assertStatus(400)->assertJson([
            "errors" => [
                "messege" => [
                    "Jabatan Already Registered"
                ]
            ]
        ]);
        
    }

    public function testJabatanStoreFailedAtasanAlreadyRegistered(){
        $this->JabatanSeedingTest();
        $username = fake()->name();
        $email = fake()->email();
        $token = Str::uuid()->toString();
        $user = User::create([
            "username" => $username,
            "email" => $email,
            'password' => Hash::make('coba123'),
            'jabatan_id' => 1,
            'validation' => true,
            'token' => $token,
        ]);
        $divisi = Divisi::create([
            "name" => "divisi",
        ]);
        $jabatan = Jabatan::create([
            "name" => "test",
            "divisi_id" => $divisi->id,
            "atasan" => true,
            "validator" => false,
        ]);
        $this->post('/jabatan', [
            "name" => "test2",
            "divisi_id" => $jabatan->divisi_id,
            "atasan" => true,
            "validator" => false,
        ], [
            "Authorization" => $token
        ])->assertStatus(400)->assertJson([
            "errors" => [
                "messege" => [
                    "Jabatan Atasan Already Registered"
                ]
            ]
        ]);
    }

    public function testJabatanShowSuccess() {
        $this->JabatanSeedingTest();
        $username = fake()->name();
        $email = fake()->email();
        $token = Str::uuid()->toString();

        $user = User::create([
            "username" => $username,
            "email" => $email,
            'password' => Hash::make('coba123'), 
            'jabatan_id' => 1,
            'validation' => true,
            'token' => $token,
        ]);
        $divisi = Divisi::create([
            "name" => "divisi",
        ]);
        $jabatan = Jabatan::create([
            "name" => "test",
            "divisi_id" => $divisi->id,
            "atasan" => false,
            "validator" => false,
        ]);
        $this->get("/jabatan/{$jabatan->id}", [
            "Authorization" => $token
        ])->assertStatus(200)->assertJson([
            "data" => [
                "name" => "test",
                "divisi_id" => $divisi->id,
                "atasan" => false,
                "validator" => false,
            ]
        ]);
    }

    public function testJabatanShowNotFound() {
        $this->JabatanSeedingTest();
        $username = fake()->name();
        $email = fake()->email();
        $token = Str::uuid()->toString();

        $user = User::create([
            "username" => $username,
            "email" => $email,
            'password' => Hash::make('coba123'), 
            'jabatan_id' => 1,
            'validation' => true,
            'token' => $token,
        ]);
        $this->get("/jabatan/1000", [
            "Authorization" => $token
        ])->assertStatus(404)->assertJson([
            "errors" => [
                "messege" => [
                    "Jabatan Not Found"
                ]
            ]
        ]);
    }

    public function testJabatanUpdateSuccess() {
        $this->JabatanSeedingTest();
        $username = fake()->name();
        $email = fake()->email();
        $token = Str::uuid()->toString();

        $user = User::create([
            "username" => $username,
            "email" => $email,
            'password' => Hash::make('coba123'), 
            'jabatan_id' => 1,
            'validation' => true,
            'token' => $token,
        ]);
        $divisi = Divisi::create([
            "name" => "divisi",
        ]);
        $jabatan = Jabatan::create([
            "name" => "test",
            "divisi_id" => $divisi->id,
            "atasan" => false,
            "validator" => false,
        ]);
        $this->patch("/jabatan/update/{$jabatan->id}", [
            "name" => "test2",
            "divisi_id" => $divisi->id,
            "atasan" => false,
            "validator" => false,
        ], [
            "Authorization" => $token
        ])->assertStatus(200)->assertJson([
            "data" => [
                "name" => "test2",
                "divisi_id" => $divisi->id,
                "atasan" => false,
                "validator" => false,
            ]
        ]);
    }

    public function testjabatanUpdateAlreadyRegistered() {
        $this->JabatanSeedingTest();
        $username = fake()->name();
        $email = fake()->email();
        $token = Str::uuid()->toString();

        $user = User::create([
            "username" => $username,
            "email" => $email,
            'password' => Hash::make('coba123'), 
            'jabatan_id' => 1,
            'validation' => true,
            'token' => $token,
        ]);
        $divisi = Divisi::create([
            "name" => "divisi",
        ]);
        $jabatan = Jabatan::create([
            "name" => "test",
            "divisi_id" => $divisi->id,
            "atasan" => false,
            "validator" => false,
        ]);
        $jabatan2 = Jabatan::create([
            "name" => "test2",
            "divisi_id" => $divisi->id,
            "atasan" => false,
            "validator" => false,
        ]);
        $this->patch("/jabatan/update/{$jabatan->id}", [
            "name" => $jabatan2->name,
            "divisi_id" => $divisi->id,
            "atasan" => false,
            "validator" => false,
        ], [
            "Authorization" => $token
        ])->assertStatus(400)->assertJson([
            "errors" => [
                "messege" => [
                    "Jabatan Already Registered"
                ]
            ]
        ]);
    }

    public function testJabatanUpdateAtasanAlreadyRegistered() {
        $this->JabatanSeedingTest();
        $username = fake()->name();
        $email = fake()->email();
        $token = Str::uuid()->toString();

        User::create([
            "username" => $username,
            "email" => $email,
            'password' => Hash::make('coba123'), 
            'jabatan_id' => 1,
            'validation' => true,
            'token' => $token,
        ]);
        $divisi = Divisi::create([
            "name" => "divisi",
        ]);
        $jabatan = Jabatan::create([
            "name" => "test",
            "divisi_id" => $divisi->id,
            "atasan" => true,
            "validator" => false,
        ]);
        $jabatan2 = Jabatan::create([
            "name" => "test2",
            "divisi_id" => $divisi->id,
            "atasan" => false,
            "validator" => false,
        ]);
        $this->patch("/jabatan/update/{$jabatan2->id}", [
            "name" => $jabatan2->name,
            "divisi_id" => $divisi->id,
            "atasan" => true,
            "validator" => false,
        ], [
            "Authorization" => $token
        ])->assertStatus(400)->assertJson([
            "errors" => [
                "messege" => [
                    "Jabatan Atasan Already Registered"
                ]
            ]
        ]);
        
    }

    public function testJabatanUpdateNotFound() {
        $this->JabatanSeedingTest();
        $username = fake()->name();
        $email = fake()->email();
        $token = Str::uuid()->toString();

        User::create([
            "username" => $username,
            "email" => $email,
            'password' => Hash::make('coba123'), 
            'jabatan_id' => 1,
            'validation' => true,
            'token' => $token,
        ]);
        $divisi = Divisi::create([
            "name" => "divisi",
        ]);
        $this->patch("/jabatan/update/1000", [
            "name" => "test2",
            "divisi_id" => $divisi->id,
            "atasan" => false,
            "validator" => false,
        ], [
            "Authorization" => $token
        ])->assertStatus(404)->assertJson([
            "errors" => [
                "messege" => [
                    "Jabatan Not Found"
                ]
            ]
        ]);
    }

    public function testJabatanDestroySuccess() {
        $this->JabatanSeedingTest();
        $username = fake()->name();
        $email = fake()->email();
        $token = Str::uuid()->toString();

        User::create([
            "username" => $username,
            "email" => $email,
            'password' => Hash::make('coba123'), 
            'jabatan_id' => 1,
            'validation' => true,
            'token' => $token,
        ]);
        $divisi = Divisi::create([
            "name" => "divisi",
        ]);
        $jabatan = Jabatan::create([
            "name" => "test",
            "divisi_id" => $divisi->id,
            "atasan" => false,
            "validator" => false,
        ]);
        $this->delete("/jabatan/delete/{$jabatan->id}", [], [
            "Authorization" => $token
        ])->assertStatus(200)->assertJson([
            "data" => true
        ]);
    }

    public function testJabartanDestroyNotFound() {
        $this->JabatanSeedingTest();
        $username = fake()->name();
        $email = fake()->email();
        $token = Str::uuid()->toString();

        User::create([
            "username" => $username,
            "email" => $email,
            'password' => Hash::make('coba123'), 
            'jabatan_id' => 1,
            'validation' => true,
            'token' => $token,
        ]);
        $this->delete("/jabatan/delete/1000", [], [
            "Authorization" => $token
        ])->assertStatus(404)->assertJson([
            "errors" => [
                "messege" => [
                    "Jabatan Not Found"
                ]
            ]
        ]);
    }

    public function testJabatanDestroyStillHaveUser() {
        $this->JabatanSeedingTest();
        $username = fake()->name();
        $email = fake()->email();
        $token = Str::uuid()->toString();

        User::create([
            "username" => $username,
            "email" => $email,
            'password' => Hash::make('coba123'), 
            'jabatan_id' => 1,
            'validation' => true,
            'token' => $token,
        ]);
        $divisi = Divisi::create([
            "name" => "divisi",
        ]);
        $jabatan = Jabatan::create([
            "name" => "test",
            "divisi_id" => $divisi->id,
            "atasan" => false,
            "validator" => false,
        ]);
        User::create([
            "username" => "test",
            "email" => "
            test@gmail.com",
            'password' => Hash::make('coba123'), 
            'jabatan_id' => $jabatan->id,
            'validation' => true,
            'token' => Str::uuid()->toString(),
        ]);
        $this->delete("/jabatan/delete/{$jabatan->id}", [], [
            "Authorization" => $token
        ])->assertStatus(400)->assertJson([
            "errors" => [
                "messege" => [
                    "Jabatan Still Have User"
                ]
            ]
        ]);
    }
}
