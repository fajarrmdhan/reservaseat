<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\CabangController;
use App\Http\Controllers\Web\AdminCabangController;

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

Route::get(
    '/dashboard',
    [DashboardController::class, 'index']
);

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