<?php

use App\Http\Middleware\ApiAuthMiddleware;
use App\Http\Middleware\ApiValidatorMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post('/users', [\App\Http\Controllers\UserController::class, 'register']);
Route::post('/users/login', [\App\Http\Controllers\UserController::class, 'login']);


Route::middleware(ApiAuthMiddleware::class)->group(function () {
    Route::get('/users/current', [\App\Http\Controllers\UserController::class, 'get']);
    Route::patch('/users/current', [\App\Http\Controllers\UserController::class, 'update']);
    Route::delete('/users/logout', [\App\Http\Controllers\UserController::class, 'logout']);
});

Route::middleware(ApiValidatorMiddleware::class)->group(function () {
    Route::get('/users', [\App\Http\Controllers\UserController::class,'getuser' ]);
    Route::patch('/users/validation/{id}', [\App\Http\Controllers\UserController::class, 'changeuservalidation'])->where('id', '[0-9]+');
    Route::patch('/users/change-jabatan/{id}', [\App\Http\Controllers\UserController::class, 'changejabatan'])->where('id', '[0-9]+');
    Route::get('/divisi', [\App\Http\Controllers\DivisiController::class,'index']);
    Route::post('/divisi', [\App\Http\Controllers\DivisiController::class,'store']);
    Route::get('/divisi/{id}', [\App\Http\Controllers\DivisiController::class,'show']);
    Route::patch('/divisi/update/{id}', [\App\Http\Controllers\DivisiController::class,'update']);
    Route::delete('/divisi/delete/{id}', [\App\Http\Controllers\DivisiController::class,'destroy']);
    Route::get('/jabatan', [\App\Http\Controllers\JabatanController::class,'index']);
    Route::post('/jabatan', [\App\Http\Controllers\JabatanController::class,'store']);
    Route::get('/jabatan/{id}', [\App\Http\Controllers\JabatanController::class,'show']);
    Route::patch('/jabatan/update/{id}', [\App\Http\Controllers\JabatanController::class,'update']);
    Route::delete('/jabatan/delete/{id}', [\App\Http\Controllers\JabatanController::class,'destroy']);
});
