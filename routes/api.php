<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CabangController;
use App\Http\Controllers\Api\MejaController;
use App\Http\Controllers\Api\ReservasiController;

Route::prefix('v1')->group(function () {

    Route::post('/register', [AuthController::class, 'register']);

    Route::post('/login', [AuthController::class, 'login']);

    Route::post('/admin/login', [AuthController::class, 'adminLogin']);

    Route::middleware('api.auth')->group(function () {
        Route::get('/profile', [AuthController::class, 'profile']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });

    Route::middleware(['api.auth', 'role:customer'])->group(function () {

        Route::post(
            '/reservasi',
            [ReservasiController::class, 'store']
        );

        Route::get(
            '/my-reservasi',
            [ReservasiController::class, 'myReservation']
        );

        Route::post(
            '/available-jam',
            [ReservasiController::class, 'availableJam']
        );

        Route::post(
            '/available-meja',
            [ReservasiController::class, 'availableMeja']
        );

        Route::get(
            '/reservasi/{id}',
            [ReservasiController::class, 'detailReservation']
        );

        Route::post(
            '/reservasi/{id}/cancel',
            [ReservasiController::class, 'cancelReservation']
        );
    });

    Route::middleware([
        'api.auth',
        'role:admin_cabang'
    ])->group(function () {

        Route::post(
            '/reservasi/check-in',
            [ReservasiController::class, 'checkIn']
        );

        Route::post(
            '/reservasi/complete',
            [ReservasiController::class, 'complete']
        );

        Route::post(
            '/reservasi/cancel',
            [ReservasiController::class, 'cancel']
        );

        Route::get(
            '/admin/reservasi',
            [
                ReservasiController::class,
                'listReservasiCabang'
            ]
        );

        Route::get(
            '/admin/reservasi/hari-ini',
            [
                ReservasiController::class,
                'reservasiHariIni'
            ]
        );

        Route::get(
            '/admin/reservasi/{id}',
            [
                ReservasiController::class,
                'detailReservasiCabang'
            ]
        );

        Route::get(
            '/admin/mejas',
            [MejaController::class, 'myCabangMejas']
        );

        Route::post(
            '/admin/mejas',
            [MejaController::class, 'store']
        );

        Route::put(
            '/admin/mejas/{id}',
            [MejaController::class, 'update']
        );

        Route::patch(
            '/admin/mejas/{id}/maintenance',
            [MejaController::class, 'maintenance']
        );

        Route::patch(
            '/admin/mejas/{id}/activate',
            [MejaController::class, 'activate']
        );
    });
});
