<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Meja;
use App\Models\Reservasi;
use Illuminate\View\View;

class AdminCabangDashboardController extends Controller
{
    public function index(): View
    {
        $cabangId = auth()->user()->cabang_id;

        Reservasi::cancelExpiredPending(cabangId: $cabangId);

        $reservasiHariIni = Reservasi::where(
                'cabang_id',
                $cabangId
            )
            ->where(
                'tanggal_booking',
                now()->format('Y-m-d')
            )
            ->count();

        $reservasiAktif = Reservasi::where(
                'cabang_id',
                $cabangId
            )
            ->whereIn(
                'status',
                Reservasi::ACTIVE_STATUSES
            )
            ->count();

        $reservasiSelesai = Reservasi::where(
                'cabang_id',
                $cabangId
            )
            ->where(
                'status',
                'completed'
            )
            ->count();

        $totalMeja = Meja::where(
                'cabang_id',
                $cabangId
            )
            ->count();

        return view(
            'admin-cabang.dashboard',
            compact(
                'reservasiHariIni',
                'reservasiAktif',
                'reservasiSelesai',
                'totalMeja'
            )
        );
    }
}
