<?php

use App\Http\Controllers\UserController;
use App\Http\Middleware\IsAdmin;
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

Route::group([
    'middleware' => 'api',
], function ($router) {

    Route::post('login', [UserController::class, 'login']);
    Route::post('logout', [UserController::class, 'logout']);
    Route::post('refresh', [UserController::class, 'refresh']);
    Route::post('me', [UserController::class, 'me']);

    Route::post('employee', [App\Http\Controllers\EmployeeController::class, 'store']);
    Route::get('employee/me', [App\Http\Controllers\EmployeeController::class, 'me']);
    Route::put('employee/{id}', [App\Http\Controllers\EmployeeController::class, 'update']);


    Route::get('cuti', [App\Http\Controllers\CutiController::class, 'index']);
    Route::get('cuti/{id}', [App\Http\Controllers\CutiController::class, 'show']);
    Route::post('cuti', [App\Http\Controllers\CutiController::class, 'store']);
    Route::put('cuti/{id}', [App\Http\Controllers\CutiController::class, 'update']);
    Route::delete('cuti/{id}', [App\Http\Controllers\CutiController::class, 'destroy']);



    Route::middleware(IsAdmin::class)->group(function () {
        Route::get('users', [App\Http\Controllers\UserController::class, 'index']);
        Route::get('users/{id}', [App\Http\Controllers\UserController::class, 'get']);
        Route::post('users', [App\Http\Controllers\UserController::class, 'store']);
        Route::put('users/{id}', [App\Http\Controllers\UserController::class, 'update']);
        Route::delete('users/{id}', [App\Http\Controllers\UserController::class, 'destroy']);
        Route::get('users/{id}/verification', [App\Http\Controllers\UserController::class, 'verification']);

        Route::get('divisi', [App\Http\Controllers\DivisiController::class, 'index']);
        Route::get('divisi/{id}', [App\Http\Controllers\DivisiController::class, 'show']);
        Route::post('divisi', [App\Http\Controllers\DivisiController::class, 'store']);
        Route::put('divisi/{id}', [App\Http\Controllers\DivisiController::class, 'update']);
        Route::delete('divisi/{id}', [App\Http\Controllers\DivisiController::class, 'destroy']);

        Route::get('jabatan', [App\Http\Controllers\JabatanController::class, 'index']);
        Route::get('jabatan/{id}', [App\Http\Controllers\JabatanController::class, 'show']);
        Route::post('jabatan', [App\Http\Controllers\JabatanController::class, 'store']);
        Route::put('jabatan/{id}', [App\Http\Controllers\JabatanController::class, 'update']);
        Route::delete('jabatan/{id}', [App\Http\Controllers\JabatanController::class, 'destroy']);


        Route::get('employee', [App\Http\Controllers\EmployeeController::class, 'index']);
        Route::get('employee/{id}', [App\Http\Controllers\EmployeeController::class, 'show']);
        Route::delete('employee/{id}', [App\Http\Controllers\EmployeeController::class, 'destroy']);


        Route::get('gaji', [App\Http\Controllers\GajiController::class, 'index']);
        Route::get('gaji/{id}', [App\Http\Controllers\GajiController::class, 'show']);
        Route::post('gaji', [App\Http\Controllers\GajiController::class, 'store']);
        Route::put('gaji/{id}', [App\Http\Controllers\GajiController::class, 'update']);
        Route::delete('gaji/{id}', [App\Http\Controllers\GajiController::class, 'destroy']);
    });

});
