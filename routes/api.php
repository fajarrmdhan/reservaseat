<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CabangController;
use App\Http\Controllers\Api\MejaController;
use App\Http\Controllers\Api\ReservasiController;
use App\Http\Controllers\Api\AdminMasterController;
use App\Http\Controllers\Api\AdminCabangController;

Route::prefix('v1')->group(function () {

    Route::post('/register', [AuthController::class, 'register']);

    Route::post('/login', [AuthController::class, 'login']);

    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);

        Route::get(
        '/cabangs',
        [CabangController::class, 'index']
    );

    Route::get('/cabangs', [CabangController::class, 'index']);

    Route::post('/admin/login', [AuthController::class, 'adminLogin']);

    Route::middleware('api.auth')->group(function () {
        Route::get('/profile', [AuthController::class, 'profile']);
        Route::get('/user', [AuthController::class, 'user']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/user/update-profile', [AuthController::class, 'updateProfile']);
        Route::post('/user/change-email', [AuthController::class, 'changeEmail']);
        Route::post('/user/change-password', [AuthController::class, 'changePassword']);
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

    Route::middleware([
        'api.auth',
        'role:admin_master'
    ])->prefix('master')
        ->group(function () {

            Route::get(
                '/dashboard',
                [AdminMasterController::class, 'dashboard']
            );

            Route::get(
                '/cabangs',
                [CabangController::class, 'index']
            );

            Route::post(
                '/cabangs',
                [CabangController::class, 'store']
            );

            Route::get(
                '/cabangs/{id}',
                [CabangController::class, 'show']
            );

            Route::patch(
                '/cabangs/{id}',
                [
                    CabangController::class,
                    'update'
                ]
            );

            Route::get(
                '/admin-cabangs',
                [AdminCabangController::class, 'index']
            );

            Route::post(
                '/admin-cabangs',
                [AdminCabangController::class, 'store']
            );

            Route::get(
                '/admin-cabangs/{id}',
                [AdminCabangController::class, 'show']
            );

            Route::patch(
                '/admin-cabangs/{id}',
                [AdminCabangController::class, 'update']
            );

            Route::patch(
                '/cabangs/{id}/deactivate',
                [CabangController::class, 'deactivate']
            );

            Route::patch(
                '/cabangs/{id}/activate',
                [CabangController::class, 'activate']
            );

            Route::patch(
                '/admin-cabangs/{id}/deactivate',
                [AdminCabangController::class, 'deactivate']
            );

            Route::patch(
                '/admin-cabangs/{id}/activate',
                [AdminCabangController::class, 'activate']
            );
        });


});
