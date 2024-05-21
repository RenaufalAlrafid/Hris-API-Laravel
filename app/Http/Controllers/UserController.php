<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Mail\VerificationMail;
use App\Models\Employee;
use App\Models\Jabatan;
use App\Models\User;
use Dotenv\Util\Str;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
     /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', "verificationEmail", "register"]]);
    }

    public function register(UserStoreRequest $request) : UserResource {
        
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
                        "errors" => "Error Tidak Bisa Pakai Jabatan"
                    ], 400));
                }
            }
        }

        $verification_email_token = $request->email . $request->name;


        $data = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'verification_email_token' => Hash::make($verification_email_token),
            'verification' => 0,
        ]);

        if($request->jabatan_id) {
            $data->jabatan_id = $request->jabatan_id;
            $data->save();
        }

        $mailData = [
            'name' => $data->name,
            'code' => $data->verification_email_token
        ];

        Mail::to($request->email)->send(new VerificationMail($mailData));


        return new UserResource($data);
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
        $data = User::where('email_verified_at', '!=', null)->get();
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
                        "errors" => "Error Tidak Bisa Pakai Jabatan"
                    ], 400));
                }
            }
        }


        $data = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'verification' => 0,
        ]);

        if($request->jabatan_id) {
            $data->jabatan_id = $request->jabatan_id;
            $data->save();
        }

        $mailData = [
            'name' => $data->name,
            'email' => $data->email,
            'id' => Hash::make($data->id)
        ];

        Mail::to($request->email)->send(new VerificationMail($data));


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


    public function verification(Request $request, Int $id) {

        $validatedData = $request->validate([
            'verification' => 'required|boolean',
            'tanggal_masuk' => 'nullable|string',
            'status' => 'nullable|string',
        ]);
        $data = User::where('id', $id)->first();

        if(!$data) {
            return new HttpResponseException(response([
                "errors" => "Data not found"
            ], 404));
        }

        $data->verification = $validatedData['verification'];
        $data->save();

        $employee = Employee::where('user_id', $id)->first();

        if (!$employee) {
            $employee = new Employee();
            $employee->user_id = $id;
            if($validatedData['status']) {
                $employee->status = $validatedData['status'];
            } else {
                $employee->status = 'Pegawai Tetap';
            }
            if($validatedData['tanggal_masuk']) {
                $employee->tanggal_masuk = $validatedData['tanggal_masuk'];
            } else {
                $employee->tanggal_masuk = date('Y-m-d');
            }
            $employee->save();
        }
        return new UserResource($data);
    }


    public function verificationEmail(Request $request) {
        $code = $request->code;
        $data = User::where('verification_email_token', $code)->first();

        if(!$data) {
            return new HttpResponseException(response([
                "errors" => "Data not found"
            ], 404));
        }

        $data->email_verified_at = date('Y-m-d H:i:s');
        $data->save();
        return redirect()->away('http://localhost:3000/login');
    }
}
