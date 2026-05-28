<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CabangController;
use App\Http\Controllers\Api\MejaController;
use App\Http\Controllers\Api\ReservasiController;

Route::prefix('v1')->group(function () {

    Route::post('/register', [AuthController::class, 'register']);

    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('api.auth')->group(function () {
        Route::get('/profile', [AuthController::class, 'profile']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });

    Route::middleware(['api.auth', 'role:customer'])->group(function () {

        Route::get('/customer-test', function () {
            return response()->json([
                'success' => true,
                'message' => 'Customer only route'
            ]);
        });
    });

    Route::middleware(['api.auth'])->group(function () {
        Route::get('/cabangs', [CabangController::class, 'index']);
        Route::post('/cabangs', [CabangController::class, 'store']);
    });

    Route::middleware(['api.auth'])->group(function () {
        Route::get('/mejas', [MejaController::class, 'index']);
        Route::post('/mejas', [MejaController::class, 'store']);
    });

    Route::middleware(['api.auth', 'role:customer'])->group(function () {
        Route::post('/reservasi', [ReservasiController::class, 'store']);
        Route::get('/my-reservasi', [ReservasiController::class, 'myReservation']);
    });
});
