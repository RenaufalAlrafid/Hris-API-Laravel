<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpadateJabatanUser;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\Jabatan;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function register(UserRegisterRequest $request) : JsonResponse {
    
        $data = $request->validated();
        
        if (User::where('username', $data['username'])->count() == 1) {
            throw new HttpResponseException(response([
                "errors" => [
                    "username" => [
                        "Username Already Registered"
                    ]
                ]
            ], 400));
        }

        if (User::where('email', $data['email'])->count() == 1) {
            throw new HttpResponseException(response([
                "errors" => [
                    "email" => [
                        "Email Already Registered"
                    ]
                ]
            ], 400));
        }

        $user = new User($data);
        $user->password = Hash::make($data['password']);
        $user->save();
        // dd($user->jabatan->name);

        // throw new HttpResponseException(response(
        //      $user->jabatan(), 400));
        return (new UserResource($user))->response()->setStatusCode(201);
    }

    public function login(UserLoginRequest $request) : UserResource {
        $data = $request->validated();
        $user = User::where('email', $data['email'])->first();
        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw new HttpResponseException(response([
                "errors" => [
                    "messege" => [
                        "Username or Password Wrong"
                    ]
                ]
            ], 401));
        } else if($user['validation'] == 0){
            throw new HttpResponseException(response([
                "errors" => [
                    "messege" => [
                        "Account Not Varified, Please Call HRD Departement"
                    ]
                ]
            ], 401));
        }

        $user->token = Str::uuid()->toString();
        $user->save();

        // dd($user);

        return new UserResource($user);
    }

    public function get(Request $request): UserResource 
    {
        $user = Auth::user();
        return new UserResource($user);


    }

    public function update(UserUpdateRequest $request): UserResource
    {
        $data = $request->validated();
        $user = Auth::user();
        // dd($data);

        $user = User::where('email', $user->email)->first();
        
        if (isset($data['username'])) {
            $user->username = $data['username'];
        }

        if (isset($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();
        return new UserResource($user);
    }


    public function logout(){
        $user = Auth::user();
        $user = User::where('email', $user->email)->first();
        $user->token = null;
        $user->save();

        return response()->json([
            'data' => 'true'
        ])->setStatusCode(200);
    }

    public function getuser(Request $request) : UserCollection {
        if ($request->input('query') ) {
            $user = User::where('username', 'like', '%' . $request['query'] . '%')->orWhere('email', 'like', '%' . $request['query'] . '%');
        } else {
            $user = User::orderby('id', 'asc');
        }
        $page = $request->input('page', 1);
        $size = $request->input('size', 10);
        $user = $user->paginate(perPage: $size, page: $page);

        return new UserCollection($user);
        
    }


    public function changeuservalidation(Int $id) {
        $user = User::where('id', $id)->first();
        $auth = Auth::user();
        if ($auth->id == $user->id) {
            throw new HttpResponseException(response([
                "errors" => [
                    "messege" => [
                        "You Dont Have Access"
                    ]
                ]
            ], 404));
        }
        if (!$user) {
            throw new HttpResponseException(response([
                "errors" => [
                    "messege" => [
                        "User Not Found"
                    ]
                ]
            ], 404));
        } 

        if($user->validation == 1) {
            $user->validation = 0;
        } else {
            $user->validation = 1;
        }
        $user->save();
        return throw new HttpResponseException(response([
            "data" => [
                "message" => [
                    "Status Validasi Berhasi Dubah"
                ]
            ]
        ], 200));
    }

    public function changejabatan(int $id, UpadateJabatanUser $request){
        $data = $request->validated();
        // dd($data);
        $user = User::where('id', $id)->first();
        if (!$user) {
            throw new HttpResponseException(response([
                "errors" => [
                    "messege" => [
                        "User Not Found"
                    ]
                ]
            ], 404));
        } 

        $jabatan = Jabatan::where('id', $data['jabatan_id'])->first();
        if (!$jabatan) {
            throw new HttpResponseException(response([
                "errors" => [
                    "messege" => [
                        "Jabatan Tidak Tersedia"
                    ]
                ]
            ], 404));
        }
        $user->jabatan_id = $data['jabatan_id'];
        $user->save();
        return throw new HttpResponseException(response([
            "data" => [
                "message" => [
                    "Jabatan Berhasil Diubah"
                ]
            ]
        ], 200));
    }

    
}
