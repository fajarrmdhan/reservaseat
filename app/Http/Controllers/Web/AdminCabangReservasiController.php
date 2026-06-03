<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Reservasi;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class AdminCabangReservasiController extends Controller
{
    public function hariIni(): View
    {
        $user = Auth::user();

        $reservasis = Reservasi::where(
            'cabang_id',
            $user->cabang_id
        )
            ->where(
                'tanggal_booking',
                now()->format('Y-m-d')
            )
            ->orderBy('jam_mulai')
            ->get();

        return view(
            'admin-cabang.reservasi.hari-ini',
            compact('reservasis')
        );
    }

    public function checkIn(string $id): RedirectResponse
    {
        Reservasi::findOrFail($id)
            ->update([
                'status' => 'checked_in'
            ]);

        return back()->with(
            'success',
            'Reservasi berhasil check in'
        );
    }

    public function complete(string $id): RedirectResponse
    {
        Reservasi::findOrFail($id)
            ->update([
                'status' => 'completed'
            ]);

        return back()->with(
            'success',
            'Reservasi selesai'
        );
    }

    public function cancel(string $id): RedirectResponse
    {
        Reservasi::findOrFail($id)
            ->update([
                'status' => 'cancelled'
            ]);

        return back()->with(
            'success',
            'Reservasi dibatalkan'
        );
    }

    public function histori(): View
    {
        $query = Reservasi::with('user')
            ->where(
                'cabang_id',
                Auth::user()->cabang_id
            )
            ->whereIn(
                'status',
                [
                    'completed',
                    'cancelled'
                ]
            );

        if (request('tanggal')) {

            $query->where(
                'tanggal_booking',
                request('tanggal')
            );
        }

        $reservasis = $query
            ->orderBy(
                'tanggal_booking',
                'desc'
            )
            ->get();

        return view(
            'admin-cabang.reservasi.histori',
            compact('reservasis')
        );
    }
}
