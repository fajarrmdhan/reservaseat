<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\CabangController;
use App\Http\Controllers\Web\AdminCabangController;
use App\Http\Controllers\Web\AuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::get(
    '/dashboard',
    function () {
        return view(
            'admin-master.dashboard'
        );
    }
);

Route::middleware([
    'auth',
    'web.role:admin_master'
])->group(function () {

    //AMdashboard
    Route::get(
        '/dashboard',
        [DashboardController::class, 'index']
    );
    //AMcabang
    Route::get(
        '/cabangs',
        [CabangController::class, 'index']
    );

    Route::get(
        '/admin-cabangs',
        [AdminCabangController::class, 'index']
    );

    Route::post(
        '/cabangs',
        [CabangController::class, 'store']
    );

    Route::patch(
        '/cabangs/{id}',
        [CabangController::class, 'update']
    );

    Route::patch(
        '/cabangs/{id}/activate',
        [CabangController::class, 'activate']
    );

    Route::patch(
        '/cabangs/{id}/deactivate',
        [CabangController::class, 'deactivate']
    );
    //AMadmincabang
    Route::post(
        '/admin-cabangs',
        [AdminCabangController::class, 'store']
    );

    Route::patch(
        '/admin-cabangs/{id}',
        [AdminCabangController::class, 'update']
    );

    Route::patch(
        '/admin-cabangs/{id}/activate',
        [AdminCabangController::class, 'activate']
    );

    Route::patch(
        '/admin-cabangs/{id}/deactivate',
        [AdminCabangController::class, 'deactivate']
    );

    Route::patch(
        '/admin-cabangs/{id}/reset-password',
        [AdminCabangController::class, 'resetPassword']
    );

});



//login page
Route::get(
    '/admin/login',
    [AuthController::class, 'loginPage']
);

Route::post(
    '/admin/login',
    [AuthController::class, 'login']
);

Route::post(
    '/admin/logout',
    [AuthController::class, 'logout']
);
