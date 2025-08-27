<?php

use App\Http\Controllers\ADMIN_ROLE\AlumniController as ADMIN_ROLEAlumniController;
use App\Http\Controllers\ADMIN_ROLE\BKController;
use App\Http\Controllers\ADMIN_ROLE\PerusahaanController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BK_ROLE\AlumniController;
use App\Http\Controllers\BK_ROLE\CountController;
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
    Route::prefix('guest')->group(function(){
        Route::get('status/count', [CountController::class, 'CountStatus']);
    });

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
        Route::put('update', [ProfileController::class, 'update']);

        Route::get('lowongan', [DaftarLowonganController::class, 'index']);
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

    Route::prefix('admin')->group(function(){
        Route::prefix('perusahaan')->group(function() {
            Route::get('get', [PerusahaanController::class, 'index']);
            Route::get('lowongan', [PerusahaanController::class, 'lowongan']);
            Route::get('show/{id}', [PerusahaanController::class, 'show']);
            Route::put('verified/{id}', [PerusahaanController::class, 'verified']);
            Route::delete('delete/{id}', [PerusahaanController::class, 'destroy']);
        });

        Route::prefix('bk')->group(function(){
            Route::get('get', [BKController::class, 'index']);
            Route::get('show/{id}', [BKController::class, 'show']);
            Route::post('create', [BKController::class, 'store']);
            Route::put('update/{id}', [BKController::class, 'update']);
            Route::delete('delete/{id}', [BKController::class, 'destroy']);
        });

        Route::prefix('alumni')->group(function(){
            Route::get('get', [ADMIN_ROLEAlumniController::class, 'index']);
            Route::get('show/{id}', [ADMIN_ROLEAlumniController::class, 'show']);
            Route::post('create', [ADMIN_ROLEAlumniController::class, 'store']);
            Route::put('update/{id}', [ADMIN_ROLEAlumniController::class, 'update']);
            Route::delete('delete/{id}', [ADMIN_ROLEAlumniController::class, 'destroy']);
        });
    });
});
