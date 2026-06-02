<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Cabang;
use App\Models\User;
use App\Models\Reservasi;

class DashboardController extends Controller
{
    public function index()
    {
        return view(
            'admin-master.dashboard',
            [
                'totalCabang' => Cabang::count(),

                'totalAdminCabang' =>
                    User::where(
                        'role',
                        'admin_cabang'
                    )->count(),

                'totalCustomer' =>
                    User::where(
                        'role',
                        'customer'
                    )->count(),

                'totalReservasi' =>
                    Reservasi::count()
            ]
        );
    }
}