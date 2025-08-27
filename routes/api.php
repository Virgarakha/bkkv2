<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BK_ROLE\AlumniController;
use App\Http\Controllers\BK_ROLE\UserController;
use App\Http\Controllers\PERUSAHAAN_ROLE\AccController;
use App\Http\Controllers\PERUSAHAAN_ROLE\LowonganController;
use App\Http\Controllers\USER_ROLE\DaftarLowonganController;
use App\Http\Controllers\USER_ROLE\ProfileController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function(){
    Route::prefix('auth')->group(function(){
        Route::post('signup', [AuthController::class, 'signup']);
        Route::post('signin', [AuthController::class, 'signin']);
        Route::post('signout', [AuthController::class, 'signout']);
        Route::post('verifikasi', [AuthController::class, 'verifikasi']);
    });

    Route::prefix('bk')->group(function(){
        Route::prefix('alumni')->group(function(){
            Route::get('get', [AlumniController::class, 'index']);
            Route::post('create', [AlumniController::class, 'store']);
            Route::put('update/{id}', [AlumniController::class, 'update']);
            Route::delete('delete/{id}', [AlumniController::class, 'destroy']);
        });

        Route::prefix('user')->group(function() {
            Route::get('get', [UserController::class, 'index']);
            Route::get('show/{id}', [UserController::class, 'show']);
            Route::post('create', [UserController::class, 'store']);
            Route::put('update/{id}', [UserController::class, 'update']);
            Route::delete('delete/{id}', [UserController::class, 'destroy']);
        });
    });

    Route::prefix('user')->group(function(){
        Route::get('get', [ProfileController::class, 'yourProfile']);
        Route::put('update/{id}', [ProfileController::class, 'update']);

        Route::post('daftar', [DaftarLowonganController::class, 'daftar']);
        Route::get('lamaranKamu', [DaftarLowonganController::class, 'lamaranKamu']);
        Route::put('cancelLamaran/{id}', [DaftarLowonganController::class, 'cancelLamaran']);
    });

    Route::prefix('perusahaan')->group(function() {
        Route::get('get', [LowonganController::class, 'index']);
        Route::get('show/{id}', [LowonganController::class, 'show']);
        Route::post('create', [LowonganController::class, 'store']);
        Route::put('update/{id}', [LowonganController::class, 'update']);
        Route::delete('delete/{id}', [LowonganController::class, 'destroy']);

        Route::get('pelamar', [AccController::class, 'pelamar']);
        Route::get('pelamar/{id}', [AccController::class, 'showPelamar']);
        Route::put('pelamar/{id}', [AccController::class, 'setSiswa']);
    });
});
