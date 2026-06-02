<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cabang;
use App\Models\User;
use App\Models\Meja;
use App\Models\Reservasi;

class AdminMasterController extends Controller
{
    public function dashboard()
    {
        return response()->json([
            'success' => true,
            'message' => 'Dashboard admin master',
            'data' => [
                'total_cabang' => Cabang::count(),
                'total_admin_cabang' => User::where(
                    'role',
                    'admin_cabang'
                )->count(),
                'total_customer' => User::where(
                    'role',
                    'customer'
                )->count(),
                'total_meja' => Meja::count(),
                'total_reservasi' => Reservasi::count(),
            ]
        ]);
    }
}