<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\Jabatan;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
     /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }



    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Get a JWT via given credentials",
     *     operationId="login",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"email", "password"},
     *                 @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *                 @OA\Property(property="password", type="string", example="password"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login successful",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="access_token", type="string"),
     *             @OA\Property(property="token_type", type="string", example="bearer"),
     *             @OA\Property(property="expires_in", type="integer", example=3600),
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Unauthorized")
     *         )
     *     )
     * )
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * @OA\Get(
     *     path="/api/me",
     *     summary="Get the authenticated User",
     *     operationId="me",
     *     security={{ "bearerAuth": { }}},
     *     tags={"Users"},
     *     @OA\Response(
     *         response=200,
     *         description="Authenticated User details",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="verification", type="boolean"),
     *             @OA\Property(property="jabatan", type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string")
     *             ),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time"),
     *         )
     *     )
     * )
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     summary="Log the user out (Invalidate the token)",
     *     operationId="logout",
     *     security={{ "bearerAuth": { }}},
     *     tags={"Authentication"},
     *     @OA\Response(
     *         response=200,
     *         description="Successfully logged out",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Successfully logged out")
     *         )
     *     )
     * )
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * @OA\Post(
     *     path="/api/refresh",
     *     summary="Refresh a token",
     *     operationId="refresh",
     *     security={{ "bearerAuth": { }}},
     *     tags={"Authentication"},
     *     @OA\Response(
     *         response=200,
     *         description="Token refreshed",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="access_token", type="string"),
     *             @OA\Property(property="token_type", type="string", example="bearer"),
     *             @OA\Property(property="expires_in", type="integer", example=3600),
     *         )
     *     )
     * )
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/users",
     *     summary="Get a list of users",
     *     operationId="index",
     *     security={{ "bearerAuth": { }}},
     *     tags={"Users"},
     *     @OA\Response(
     *         response=200,
     *         description="List of users",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="name", type="string"),
     *                     @OA\Property(property="email", type="string", format="email"),
     *                     @OA\Property(property="verification", type="boolean"),
     *                     @OA\Property(property="jabatan", type="object",
     *                         @OA\Property(property="id", type="integer"),
     *                         @OA\Property(property="name", type="string")
     *                     ),
     *                     @OA\Property(property="created_at", type="string", format="date-time"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time"),
     *                 )
     *             ),
     *         )
     *     )
     * )
     */
    public function index() : UserCollection {
        $data = User::all();
        return new UserCollection($data);
    }

    /**
     * @OA\Get(
     *     path="/api/users/{id}",
     *     summary="Get a user by ID",
     *     operationId="get",
     *     security={{ "bearerAuth": { }}},
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="User ID",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User details",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="verification", type="boolean"),
     *             @OA\Property(property="jabatan", type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string")
     *             ),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Data not found")
     *         )
     *     )
     * )
     */
    Public function get(Int $id) : UserResource {
        $data = User::where('id', $id)->first();

        if(!$data) {
            return new HttpResponseException(response([
                "errors" => "Data not found"
            ], 404));
        }
        return new UserResource($data);
    }

    /**
     * @OA\Post(
     *     path="/api/users",
     *     summary="Create a new user",
     *     operationId="store",
     *     security={{ "bearerAuth": { }}},
     *     tags={"Users"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"name", "email", "password", "verification", "jabatan_id"},
     *                 @OA\Property(property="name", type="string", maxLength=255, example="New User Name"),
     *                 @OA\Property(property="email", type="string", format="email", example="newuser@example.com"),
     *                 @OA\Property(property="password", type="string", minLength=6, example="password"),
     *                 @OA\Property(property="verification", type="boolean", example=true),
     *                 @OA\Property(property="jabatan_id", type="integer", example=1),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User created",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="verification", type="boolean"),
     *             @OA\Property(property="jabatan", type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string")
     *             ),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Jabatan not found or Jabatan Atasan Sudah Terisi")
     *         )
     *     )
     * )
     */
    public function store(UserStoreRequest $request) : UserResource {
        
        if($request->jabatan_id) {
            $jabatan = Jabatan::where('id', $request->jabatan_id)->first();
            if(!$jabatan) {
                throw new HttpResponseException(response([
                    "errors" => "Jabatan not found"
                ], 404));
            }
            if($jabatan->atasan == true) {
                $user = User::where('jabatan_id', $request->jabatan_id)->first();
                if($user) {
                    throw new HttpResponseException(response([
                        "errors" => "Jabatan Atasan Sudah Terisi"
                    ], 400));
                }
            }
        }


        $data = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'verification' => 1,
        ]);

        if($request->jabatan_id) {
            $data->jabatan_id = $request->jabatan_id;
            $data->save();
        }


        return new UserResource($data);
    }


    /**
     * @OA\Put(
     *     path="/api/users/{id}",
     *     summary="Update an existing user",
     *     operationId="update",
     *     security={{ "bearerAuth": { }}},
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the user to be updated",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"name", "email", "password", "verification", "jabatan_id"},
     *                 @OA\Property(property="name", type="string", maxLength=255, example="Updated User Name"),
     *                 @OA\Property(property="email", type="string", format="email", example="updated@example.com"),
     *                 @OA\Property(property="password", type="string", minLength=6, example="newpassword"),
     *                 @OA\Property(property="verification", type="boolean", example=true),
     *                 @OA\Property(property="jabatan_id", type="integer", example=1),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User updated",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="verification", type="boolean"),
     *             @OA\Property(property="jabatan", type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string")
     *             ),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Jabatan not found or Jabatan Atasan Sudah Terisi")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Data not found")
     *         )
     *     )
     * )
     */
    public function update(UserUpdateRequest $request, Int $id) : UserResource {
        $data = User::where('id', $id)->first();

        if(!$data) {
            return new HttpResponseException(response([
                "errors" => "Data not found"
            ], 404));
        }

        if($request->name) {
            $data->name = $request->name;
        }
        if($request->email) {
            $data->email = $request->email;
        }
        if($request->password) {
            $data->password = Hash::make($request->password);
        }
        if($request->verification) {
            $data->verification = $request->verification;
        }
        if($request->jabatan_id) {
            $jabatan = Jabatan::where('id', $request->jabatan_id)->first();
            if(!$jabatan) {
                throw new HttpResponseException(response([
                    "errors" => "Jabatan not found"
                ], 404));
            }
            if($jabatan->atasan == true) {
                // check if jabatan atasan is already filled without user
                $user = User::where('jabatan_id', $request->jabatan_id)->where('id', '!=', $id)->first();
                if($user) {
                    throw new HttpResponseException(response([
                        "errors" => "Jabatan Atasan Sudah Terisi"
                    ], 400));
                }
            }
            $data->jabatan_id = $request->jabatan_id;
        }
        return new UserResource($data);
    }

    /**
     * @OA\Delete(
     *     path="/api/users/{id}",
     *     summary="Delete a user",
     *     operationId="delete",
     *     security={{ "bearerAuth": { }}},
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the user to be deleted",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User deleted",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Successfully deleted user")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Data not found")
     *         )
     *     )
     * )
     */

    public function delete(Int $id) : UserResource {
        $data = User::where('id', $id)->first();

        if(!$data) {
            return new HttpResponseException(response([
                "errors" => "Data not found"
            ], 404));
        }

        $data->delete();
        return new UserResource($data);
    }
}
