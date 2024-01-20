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

class DivisiTest extends TestCase
{
    function divisiSeedingTest() {
        $this->seed([UserSeeder::class]);
        $this->seed([DivisiSeeder::class]);
        $this->seed([JabatanSeeder::class]);
    }
    public function testGetDivisiSuccess() {
        $this->divisiSeedingTest();
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
        $divisi = Divisi::all()->count();
        
        $response = $this->get('/divisi', [
            'Authorization' => $token,
        ])->assertStatus(200)->json();
        Log::info(json_encode($response, JSON_PRETTY_PRINT));
        if ($divisi < 10) {
            self::assertEquals($divisi, count($response['data']));
        } else {
            self::assertEquals(10, count($response['data']));
        }
        self::assertEquals($divisi, $response['meta']['total']);
    }

    Public function testGetDivisiChangePageAndSize() {
        $this->divisiSeedingTest();
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

        for ($i=0; $i < 10; $i++) { 
            $fake = fake()->jobTitle();
            $number = fake()->phoneNumber();
            Divisi::create([
                "name" => "divisi {$fake} {$number}",
            ]);
        }
        $divisi = Divisi::all()->count();

        $response = $this->get('/divisi?page=2&size=1', [
            'Authorization' => $token,
        ])->assertStatus(200)->json();
        Log::info(json_encode($response, JSON_PRETTY_PRINT));
        self::assertEquals(1, count($response['data']));
        self::assertEquals($divisi, $response['meta']['total']);
        self::assertEquals(2, $response['meta']['current_page']);
    }

    public function testGetDivisionByName() {
        $this->divisiSeedingTest();
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
    
        for ($i=0; $i < 10; $i++) {
            $name = fake()->jobTitle();
            Divisi::create([
                "name" => "test {$name} {$i}",
            ]);
        }
        $divisi_filter = Divisi::where('name', 'like', '%' . "test" . '%')->count();

        $response = $this->get('/divisi?query=test', [
            'Authorization' => $token,
        ])->assertStatus(200)->json();
        Log::info(json_encode($response, JSON_PRETTY_PRINT));
        self::assertEquals(10, count($response['data']));
        self::assertEquals($divisi_filter, $response['meta']['total']);
        
    }

    public function testGetDivisionByNameNotFound() {
        $this->divisiSeedingTest();
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
    
        

        $response = $this->get('/divisi?query=wpdnponqfwoinqpfnqwifnqn fw', [
            'Authorization' => $token,
        ])->assertStatus(200)->json();
        Log::info(json_encode($response, JSON_PRETTY_PRINT));
        self::assertEquals(0, count($response['data']));
    }

    public function testStoreDivisiSuccess() {
        $this->divisiSeedingTest();
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
    
        $this->post('/divisi', [
            'name' => 'test',
        ], [
            'Authorization' => $token,
        ])->assertStatus(200)->assertJson([
            "data" => [
                "name" => "test",
            ]
        ]);
    }

    public function testStoreDivisiFailedNameValidation(){
        $this->divisiSeedingTest();
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
    
        $this->post('/divisi', [
            'name' => '',
        ], [
            'Authorization' => $token,
        ])->assertStatus(400)->assertJson([
            "errors" => [
                "name" => [
                    "The name field is required."
                ]
            ]
        ]);       
    }

    public function testStoreDivisiSameName() {
        $this->divisiSeedingTest();
        $username = fake()->name();
        $email = fake()->email();
        $token = Str::uuid()->toString();

        $divisi = Divisi::create([
            "name" => "test",
        ]);

        User::create([
            "username" => $username,
            "email" => $email,
            'password' => Hash::make('coba123'),
            "jabatan_id" => 1,
            'validation' => true,
            'token' => $token,
        ]);
    
        $this->post('/divisi', [
            'name' => 'test',
        ], [
            'Authorization' => $token,
        ])->assertStatus(400)->assertJson([
            "errors" => [
                "messege" => [
                    "Divisi Already Registered"
                ]
            ]
        ]);
        
    }

    public function testShowDivisibyId() {
        $this->divisiSeedingTest();
        $username = fake()->name();
        $email = fake()->email();
        $token = Str::uuid()->toString();

        $divisi = Divisi::create([
            "name" => "test",
        ]);

        User::create([
            "username" => $username,
            "email" => $email,
            'password' => Hash::make('coba123'),
            "jabatan_id" => 1,
            'validation' => true,
            'token' => $token,
        ]);
    
        $this->get("/divisi/{$divisi->id}", [
            'Authorization' => $token,
        ])->assertStatus(200)->assertJson([
            "data" => [
                "name" => "test",
            ]
        ]);
    }

    public function testShowDivisiNotFound() {
        $this->divisiSeedingTest();
        $username = fake()->name();
        $email = fake()->email();
        $token = Str::uuid()->toString();

        $divisi = Divisi::create([
            "name" => "test",
        ]);

        User::create([
            "username" => $username,
            "email" => $email,
            'password' => Hash::make('coba123'),
            "jabatan_id" => 1,
            'validation' => true,
            'token' => $token,
        ]);
    
        $this->get("/divisi/100000", [
            'Authorization' => $token,
        ])->assertStatus(404)->assertJson([
            "errors" => [
                "messege" => [
                    "Divisi Not Found"
                ]
            ]
        ]);
    }

    public function testUpdateDivisiSuccess() {
        $this->divisiSeedingTest();
        $username = fake()->name();
        $email = fake()->email();
        $token = Str::uuid()->toString();

        $divisi = Divisi::create([
            "name" => "test",
        ]);

        User::create([
            "username" => $username,
            "email" => $email,
            'password' => Hash::make('coba123'),
            "jabatan_id" => 1,
            'validation' => true,
            'token' => $token,
        ]);
    
        $this->patch("/divisi/update/{$divisi->id}", [
            'name' => 'test2',
        ], [
            'Authorization' => $token,
        ])->assertStatus(200)->assertJson([
            "data" => [
                "name" => "test2",
            ]
        ]);
    }

    public function testUpdateDivisiFailedSameName() {
        $this->divisiSeedingTest();
        $username = fake()->name();
        $email = fake()->email();
        $token = Str::uuid()->toString();

        $divisi = Divisi::create([
            "name" => "test",
        ]);

        $divisi2 = Divisi::create([
            "name" => "test2",
        ]);

        User::create([
            "username" => $username,
            "email" => $email,
            'password' => Hash::make('coba123'),
            "jabatan_id" => 1,
            'validation' => true,
            'token' => $token,
        ]);
    
        $this->patch("/divisi/update/{$divisi->id}", [
            'name' => 'test2',
        ], [
            'Authorization' => $token,
        ])->assertStatus(400)->assertJson([
            "errors" => [
                "messege" => [
                    "Divisi Already Registered"
                ]
            ]
        ]);
    }

    public function testUpdateDivisiFailedNotFound() {
        $this->divisiSeedingTest();
        $username = fake()->name();
        $email = fake()->email();
        $token = Str::uuid()->toString();

        $divisi = Divisi::create([
            "name" => "test",
        ]);


        User::create([
            "username" => $username,
            "email" => $email,
            'password' => Hash::make('coba123'),
            "jabatan_id" => 1,
            'validation' => true,
            'token' => $token,
        ]);
    
        $this->patch("/divisi/update/100000", [
            'name' => 'test2',
        ], [
            'Authorization' => $token,
        ])->assertStatus(404)->assertJson([
            "errors" => [
                "messege" => [
                    "Divisi Not Found"
                ]
            ]
        ]);
        
    }


    public function testDeleteDivisionSuccess() {
        $this->divisiSeedingTest();
        $username = fake()->name();
        $email = fake()->email();
        $token = Str::uuid()->toString();

        User::create([
            "username" => $username,
            "email" => $email,
            'password' => Hash::make('coba123'),
            "jabatan_id" => 1,
            'validation' => true,
            'token' => $token,
        ]);

        $divisi = Divisi::create([
            "name" => "test",
        ]);

        $this->delete("/divisi/delete/{$divisi->id}", [], [
            'Authorization' => $token,
        ])->assertStatus(200)->assertJson([
            "data" => [
                "message" => [
                    "Divisi Berhasil Dihapus"
                ]
            ]
        ]);
    }

    public function testDeleteDivisionNotfound() {
        $this->divisiSeedingTest();
        $username = fake()->name();
        $email = fake()->email();
        $token = Str::uuid()->toString();

        User::create([
            "username" => $username,
            "email" => $email,
            'password' => Hash::make('coba123'),
            "jabatan_id" => 1,
            'validation' => true,
            'token' => $token,
        ]);

        $divisi = Divisi::create([
            "name" => "test",
        ]);

        $this->delete("/divisi/delete/100000", [], [
            'Authorization' => $token,
        ])->assertStatus(404)->assertJson([
            "errors" => [
                "messege" => [
                    "Divisi Not Found"
                ]
            ]
        ]);
    }

    public function testDeleteDivisionHavePosition() {
        $this->divisiSeedingTest();
        $username = fake()->name();
        $email = fake()->email();
        $token = Str::uuid()->toString();

        User::create([
            "username" => $username,
            "email" => $email,
            'password' => Hash::make('coba123'),
            "jabatan_id" => 1,
            'validation' => true,
            'token' => $token,
        ]);

        $divisi = Divisi::create([
            "name" => "test",
        ]);

        $jabatan = Jabatan::create([
            "name" => "test",
            "divisi_id" => $divisi->id,
            "atasan" => 1,
            "validator" => 1,
        ]);

        $this->delete("/divisi/delete/{$divisi->id}", [], [
            'Authorization' => $token,
        ])->assertStatus(404)->assertJson([
            "errors" => [
                "messege" => [
                    "Divisi Masih Memiliki Jabatan yang aktif"
                ]
            ]
        ]);
    }
}
