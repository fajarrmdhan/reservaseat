<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Reservasi;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminCabangReservasiController extends Controller
{
    public function hariIni(): View
    {
        $user = Auth::user();

        Reservasi::cancelExpiredPending(cabangId: $user->cabang_id);

        $reservasis = Reservasi::with([
            'user',
            'meja'
        ])
            ->where(
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

    public function scan(): View
    {
        return view('admin-cabang.reservasi.scan');
    }

    public function processScan(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'kode_reservasi' => 'required|string',
        ]);

        $kodeReservasi = trim($validated['kode_reservasi']);
        $user = Auth::user();

        Reservasi::cancelExpiredPending(cabangId: $user->cabang_id);

        $reservasi = Reservasi::with([
            'user',
            'meja'
        ])
            ->where('kode_reservasi', $kodeReservasi)
            ->where('cabang_id', $user->cabang_id)
            ->first();

        if (!$reservasi) {
            return response()->json([
                'success' => false,
                'message' => 'Reservasi tidak ditemukan untuk cabang ini.'
            ], 404);
        }

        if ($reservasi->autoCancelIfExpired()) {
            return response()->json([
                'success' => false,
                'message' => 'Reservasi sudah melewati batas toleransi 30 menit dan otomatis dibatalkan.',
                'data' => $this->scanReservationPayload($reservasi->fresh([
                    'user',
                    'meja'
                ]))
            ], 422);
        }

        if (in_array($reservasi->status, ['pending', 'paid'])) {
            $reservasi->update([
                'status' => 'checked_in'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Check in berhasil.',
                'action' => 'checked_in',
                'data' => $this->scanReservationPayload($reservasi->fresh([
                    'user',
                    'meja'
                ]))
            ]);
        }

        if ($reservasi->status === 'checked_in') {
            $reservasi->update([
                'status' => 'completed'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Reservasi berhasil diselesaikan.',
                'action' => 'completed',
                'data' => $this->scanReservationPayload($reservasi->fresh([
                    'user',
                    'meja'
                ]))
            ]);
        }

        if ($reservasi->status === 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'Reservasi ini sudah selesai.',
                'data' => $this->scanReservationPayload($reservasi)
            ], 422);
        }

        if ($reservasi->status === 'cancelled') {
            return response()->json([
                'success' => false,
                'message' => 'Reservasi ini sudah dibatalkan.',
                'data' => $this->scanReservationPayload($reservasi)
            ], 422);
        }

        return response()->json([
            'success' => false,
            'message' => 'Status reservasi tidak dapat diproses.',
            'data' => $this->scanReservationPayload($reservasi)
        ], 422);
    }

    public function checkIn(string $id): RedirectResponse
    {
        $reservasi = Reservasi::findOrFail($id);

        if ($reservasi->autoCancelIfExpired()) {
            return back()->with(
                'error',
                'Reservasi sudah melewati batas toleransi 30 menit dan otomatis dibatalkan'
            );
        }

        $reservasi->update([
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
        Reservasi::cancelExpiredPending(
            cabangId: Auth::user()->cabang_id
        );

        $query = Reservasi::with([
            'user',
            'meja'
        ])
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

    private function scanReservationPayload(Reservasi $reservasi): array
    {
        return [
            'kode_reservasi' => $reservasi->kode_reservasi,
            'nama_customer' => $reservasi->user->name ?? '-',
            'nomor_meja' => $reservasi->meja->nomor_meja ?? '-',
            'tanggal_booking' => $reservasi->tanggal_booking,
            'jam_mulai' => $reservasi->jam_mulai,
            'status' => $reservasi->status,
        ];
    }
}
