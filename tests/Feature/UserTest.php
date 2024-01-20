<?php

namespace Tests\Feature;

use App\Models\Jabatan;
use App\Models\User;
use Database\Seeders\UserSeeder;
use GuzzleHttp\Psr7\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;
use Illuminate\Support\Str;

class UserTest extends TestCase
{
    public function testRegisterSuccess() {
        
        $this->post('/users', [
            "username" => fake()->name(),
            "email" => fake()->email(),
            "password" => "cobauser",
            "jabatan_id" => 1 
        ])->assertStatus(201);
    }

    public function testloginSuccess() {
        $username = fake()->name();
        $email = fake()->email();
        User::create([
            "username" => $username,
            "email" => $email,
            'password' => Hash::make('coba123'),
            'jabatan_id' => 1,
            'validation' => true,
        ]);
        
        $this->post('/users/login', [
            "password" => "coba123",
            "email" => $email,
        ])->ssertStatus(200)->assertJson([
            "data" => [
                "username" => $username,
                "email" => $email,
                "jabatan_id" => 1,
                "jabatan" => "Full Stack Developer",
            ]
        ]);  

        $user = User::where('username', $username)->first();
        self::assertNotNull($user->token);
    }

    public function testloginErorUsernameAndPassword() {
        $this->post('/users/login', [
            "password" => "Renaufal Alrafid Isnanto",
            "email" => "yiobnewiopfbwieo"
        ])->assertStatus(401)->assertJson([
            "errors" => [
                "messege" => [
                    "Username or Password Wrong"
                ]
            ]
        ]);
    }

    public function testAccountNotValidation () {
        $username = fake()->name();
        $email = fake()->email();
        User::create([
            "username" => $username,
            "email" => $email,
            'password' => Hash::make('coba123'),
            'jabatan_id' => 1,
            'validation' => false,
        ]);
        
        $this->post('/users/login', [
            "password" => "coba123",
            "email" => $email,
        ])->assertStatus(401)->assertJson([
            "errors" => [
                "messege" => [
                    "Account Not Varified, Please Call HRD Departement"
                ]
            ]
        ]);  
    }

    function testGetSuccess() {
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

        $this->get('/users/current', [
            'Authorization' => $token
        ])->assertStatus(200)
            ->assertJson([
                'data' => [
                    'username' => $username,
                    'email' => $email,
                ]
            ]);
    }

    public function testGetUnauthorized()
    {
        $this->get('/users/current')
            ->assertStatus(401)
            ->assertJson([
                'errors' => [
                    'message' => [
                        'unauthorized'
                    ]
                ]
            ]);

    }

    public function testUpdatePasswordSuccess()
    {
        $this->seed([UserSeeder::class]);
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
        $oldUser = User::where('email', $email)->first();

        $this->patch('/users/current',
            [
                'password' => 'baru'
            ],
            [
                'Authorization' => $token
            ]
        )->assertStatus(200)
            ->assertJson([
                'data' => [
                    'username' => $username,
                ]
            ]);

        $newUser = User::where('email', $email)->first();
        self::assertNotEquals($oldUser->password, $newUser->password);
    }

    public function testUpdateNameSuccess()
    {
        $this->seed([UserSeeder::class]);

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
        $oldUser = User::where('email', $email)->first();

        $this->patch('/users/current',
            [
                'username' => 'Eko'
            ],
            [
                'Authorization' => $token
            ]
        )->assertStatus(200)
            ->assertJson([
                'data' => [
                    'username' => 'Eko',
                ]
            ]);

        $newUser = User::where('email', $email)->first();
        self::assertNotEquals($oldUser->username, $newUser->username);
    }

    public function testUpdateFailed()
    {
        $this->seed([UserSeeder::class]);
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

        $this->patch('/users/current',
            [
                'username' => 'EkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEkoEko'
            ],
            [
                'Authorization' => $token
            ]
        )->assertStatus(400)
        ->assertJson([
                'errors' => [
                    'username' => [
                        "The username field must not be greater than 100 characters."
                    ]
                ]
            ]);
    }

    public function testLogoutSuccess() {
        $this->seed([UserSeeder::class]);
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

        $this->delete('/users/logout', headers:[
            'Authorization' => $token
        ])->assertStatus(200)->assertJson([
            'data' => true
        ]);
        $user = User::where('username', $username)->first();
        self::assertNull($user->token);
    }

    public function testLogoutFailed() {
        $this->seed([UserSeeder::class]);
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

        $this->delete('/users/logout')->assertStatus(401)->assertJson([
            'errors' => [
                'message' => [
                    'unauthorized'
                ]
            ]
        ]);
    }


    public function testSearchByUsername() {
        $this->seed([UserSeeder::class]);
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
        for ($i=0; $i < 20; $i++) { 
            User::create([
                "username" => "username {$i}" ,
                "email" => fake()->email(),
                'password' => Hash::make('coba123'),
                'jabatan_id' => 1,
                'validation' => true,
                'token' => Str::uuid()->toString(),
            ]);
        }

        $response = $this->get('/users?query=username', [
            'Authorization' => $token
        ])->assertStatus(200)->json();
        
        Log::info(json_encode($response, JSON_PRETTY_PRINT));
        self::assertEquals(10, count($response['data']));
        self::assertEquals(20, $response['meta']['total']);

    }


    public function testSearchByEmail() {
        $this->seed([UserSeeder::class]);
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
        for ($i=0; $i < 20; $i++) { 
            User::create([
                "username" => fake()->name(),
                "email" => "email{$i}@gmail.com",
                'password' => Hash::make('coba123'),
                'jabatan_id' => 1,
                'validation' => true,
                'token' => Str::uuid()->toString(),
            ]);
        }

        $response = $this->get('/users?query=email', [
            'Authorization' => $token
        ])->assertStatus(200)->json();
        
        Log::info(json_encode($response, JSON_PRETTY_PRINT));
        self::assertEquals(10, count($response['data']));
        self::assertEquals(20, $response['meta']['total']);
    }

    public function testSearchPage() {
        $this->seed([UserSeeder::class]);
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
        for ($i=0; $i < 20; $i++) { 
            User::create([
                "username" => fake()->name(),
                "email" => fake()->name(),
                'password' => Hash::make('coba123'),
                'jabatan_id' => 1,
                'validation' => true,
                'token' => Str::uuid()->toString(),
            ]);
        }

        $response = $this->get('/users?page=2&size=5', [
            'Authorization' => $token
        ])->assertStatus(200)->json();

        Log::info(json_encode($response, JSON_PRETTY_PRINT));
        self::assertEquals(5, count($response['data']));
        self::assertEquals(21, $response['meta']['total']);
        self::assertEquals(2, $response['meta']['current_page']);
    }

    public function testSearchnotFound() {
        $this->seed([UserSeeder::class]);
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

        $response = $this->get('/users?query=ndpwqnfpqwnpinghqwge', [
            'Authorization' => $token
        ])->assertStatus(200)->json();

        Log::info(json_encode($response, JSON_PRETTY_PRINT));
        self::assertEquals(0, count($response['data']));
        self::assertEquals(0, $response['meta']['total']);
    }

    public function notAuthorizationsearch() {
        $this->get('/users')->assertStatus(401)->assertJson([
            'errors' => [
                'message' => [
                    'unauthorized'
                ]
            ]
        ]);


    }

    public function validationUserSuccess() {
        $this->seed([UserSeeder::class]);
        $username = fake()->name();
        $email = fake()->email();
        $token = Str::uuid()->toString();

        $auth = User::create([
            "username" => $username,
            "email" => $email,
            'password' => Hash::make('coba123'), 
            'jabatan_id' => 1,
            'validation' => true,
            'token' => $token,
        ]);
        $user = User::create([
            "username" => fake()->name(),
            "email" => fake()->email(),
            'password' => Hash::make('coba123'), 
            'jabatan_id' => 1,
            'validation' => false,
            'token' => Str::uuid()->toString(),
        ]);
        $this->patch("/users/validation/{$user->id}", [
            'validation' => true
        ], [
            'Authorization' => $token
        ])->assertStatus(200)->assertJson([
            'data' => true
        ]);
        $user = User::where('id', $user->id)->first();
        self::assertTrue($user->validation);
    }

    public function validationUserNotfound() {
        $this->seed([UserSeeder::class]);
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
        
        $this->patch('/users/validation/100', [
            'validation' => true
        ], [
            'Authorization' => $token
        ])->assertStatus(404)->assertJson([
            'errors' => [
                'message' => [
                    'User Not Found'
                ]
            ]
        ]);
    }

    public function testUserNotHaveAccess() {
        $this->seed([UserSeeder::class]);
        $username = fake()->name();
        $email = fake()->email();
        $token = Str::uuid()->toString();

        $auth = User::create([
            "username" => $username,
            "email" => $email,
            'password' => Hash::make('coba123'), 
            'jabatan_id' => 1,
            'validation' => true,
            'token' => $token,
        ]);
        $this->patch("/users/validation/{$auth->id}", [
            'validation' => true
        ], [
            'Authorization' => $token
        ])->assertStatus(404)->assertJson([
            "errors" => [
                "messege" => [
                    "You Dont Have Access"
                ]
            ]
        ]);
    }

    public function testChangeJabatanSuccess() {
        $this->seed([UserSeeder::class]);
        $username = fake()->name();
        $email = fake()->email();
        $token = Str::uuid()->toString();

        $auth = User::create([
            "username" => $username,
            "email" => $email,
            'password' => Hash::make('coba123'), 
            'jabatan_id' => 1,
            'validation' => true,
            'token' => $token,
        ]);

        $jabatan = Jabatan::create([
            "name" => fake()->jobTitle(),
            "divisi_id" => 1,
            "atasan" => true,
            "validator" => true,
        ]);
        $user = User::create([
            "username" => fake()->name(),
            "email" => fake()->email(),
            'password' => Hash::make('coba123'), 
            'jabatan_id' => 1,
            'validation' => true,
        ]);
        $this->patch("/users/change-jabatan/{$user->id}", [
            'jabatan_id' => $jabatan->id
        ], [
            'Authorization' => $token
        ])->assertStatus(200)->assertJson([
            'data' => true
        ]);
        $user = User::where('id', $user->id)->first();
        self::assertEquals($jabatan->id, $user->jabatan_id);
    }

    public function testChangeJabatanUserNotfound() {
        $this->seed([UserSeeder::class]);
        $username = fake()->name();
        $email = fake()->email();
        $token = Str::uuid()->toString();

        $auth = User::create([
            "username" => $username,
            "email" => $email,
            'password' => Hash::make('coba123'), 
            'jabatan_id' => 1,
            'validation' => true,
            'token' => $token,
        ]);

        $jabatan = Jabatan::create([
            "name" => fake()->jobTitle(),
            "divisi_id" => 1,
            "atasan" => false,
            "validator" => true,
        ]);
        $user = User::create([
            "username" => fake()->name(),
            "email" => fake()->email(),
            'password' => Hash::make('coba123'), 
            'jabatan_id' => 1,
            'validation' => true,
        ]);
        $this->patch("/users/change-jabatan/100", [
            'jabatan_id' => $jabatan->id
        ], [
            'Authorization' => $token
        ])->assertStatus(404)->assertJson([
            "errors" => [
                "messege" => [
                    "User Not Found"
                ]
            ]
        ]);
        
    }

    public function testChangeJabatanJabatanNotFound() {
        $this->seed([UserSeeder::class]);
        $username = fake()->name();
        $email = fake()->email();
        $token = Str::uuid()->toString();

        $auth = User::create([
            "username" => $username,
            "email" => $email,
            'password' => Hash::make('coba123'), 
            'jabatan_id' => 1,
            'validation' => true,
            'token' => $token,
        ]);

        $jabatan = Jabatan::create([
            "name" => fake()->jobTitle(),
            "divisi_id" => 1,
            "atasan" => false,
            "validator" => true,
        ]);
        $user = User::create([
            "username" => fake()->name(),
            "email" => fake()->email(),
            'password' => Hash::make('coba123'), 
            'jabatan_id' => 1,
            'validation' => true,
        ]);
        $this->patch("/users/change-jabatan/{$user->id}", [
            'jabatan_id' => 100
        ], [
            'Authorization' => $token
        ])->assertStatus(404)->assertJson([
            "errors" => [
                "messege" => [
                    "Jabatan Tidak Tersedia"
                ]
            ]
        ]);
    }
    
    
}
