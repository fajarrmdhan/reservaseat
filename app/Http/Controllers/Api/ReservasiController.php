<?php

namespace App\Http\Controllers\Api;

use App\Helpers\SlotHelper;
use App\Http\Controllers\Controller;
use App\Models\Meja;
use App\Models\Reservasi;
use Illuminate\Http\Request;

class ReservasiController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([

            'cabang_id' => 'required',

            'meja_id' => 'required',

            'tanggal_booking' => 'required|date',

            'jam_mulai' => 'required',

            'durasi' => 'required|numeric|min:1|max:4',

            'catatan' => 'nullable|string',
        ]);

        $user = $request->auth_user;

        $meja = Meja::find($request->meja_id);

        $cabang = $meja->cabang;

        $jamMulai = \Carbon\Carbon::createFromFormat(
            'H:i',
            $request->jam_mulai
        );

        $jamTutup = \Carbon\Carbon::createFromFormat(
            'H:i',
            $cabang->jam_tutup
        );

        $jamSelesai = $jamMulai
            ->copy()
            ->addHours($request->durasi);

        if ($jamSelesai->gt($jamTutup)) {

            return response()->json([
                'success' => false,
                'message' => 'Reservasi melebihi jam operasional cabang'
            ], 422);
        }

        if (!$meja) {

            return response()->json([
                'success' => false,
                'message' => 'Meja tidak ditemukan'
            ], 404);
        }

        if ($meja->status !== 'active') {

            return response()->json([
                'success' => false,
                'message' => 'Meja tidak tersedia'
            ], 422);
        }

        $blockedSlots =
            SlotHelper::generateBlockedSlots(
                $request->jam_mulai,
                $request->durasi
            );

        $reservasis = Reservasi::where(
            'meja_id',
            $request->meja_id
        )
            ->where(
                'tanggal_booking',
                $request->tanggal_booking
            )
            ->whereIn('status', [
                'pending',
                'paid',
                'checked_in'
            ])
            ->get();

        foreach ($reservasis as $reservasi) {

            $overlap = array_intersect(
                $blockedSlots,
                $reservasi->blocked_slots
            );

            if (!empty($overlap)) {

                return response()->json([
                    'success' => false,
                    'message' => 'Slot meja sudah dibooking'
                ], 422);
            }
        }

        $kodeReservasi =
            'RSV-' .
            strtoupper(substr(uniqid(), -6));

        $reservasi = Reservasi::create([

            'user_id' => $user->_id,

            'cabang_id' => $request->cabang_id,

            'meja_id' => $request->meja_id,

            'tanggal_booking' =>
            $request->tanggal_booking,

            'jam_mulai' =>
            $request->jam_mulai,

            'durasi' =>
            $request->durasi,

            'blocked_slots' =>
            $blockedSlots,

            'catatan' =>
            $request->catatan,

            'source' => 'online',

            'status' => 'pending',

            'kode_reservasi' =>
            $kodeReservasi,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Reservasi berhasil dibuat',
            'data' => $reservasi
        ]);
    }

    public function myReservation(Request $request)
    {
        $user = $request->auth_user;

        $reservasis = Reservasi::where(
            'user_id',
            $user->_id
        )
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'List reservasi',
            'data' => $reservasis
        ]);
    }

    public function availableMeja(Request $request)
    {
        $request->validate([

            'cabang_id' => 'required',

            'tanggal_booking' => 'required|date',

            'jam_mulai' => 'required',

            'durasi' => 'required|numeric|min:1|max:4',

            'kapasitas' => 'required|numeric|min:1',
        ]);

        $blockedSlots =
            SlotHelper::generateBlockedSlots(
                $request->jam_mulai,
                $request->durasi
            );

        $mejas = Meja::where(
            'cabang_id',
            $request->cabang_id
        )
            ->where(
                'status',
                'active'
            )
            ->where(
                'kapasitas',
                (int) $request->kapasitas
            )
            ->get();

        $availableMejas = [];

        foreach ($mejas as $meja) {

            $reservasis = Reservasi::where(
                'meja_id',
                $meja->_id
            )
                ->where(
                    'tanggal_booking',
                    $request->tanggal_booking
                )
                ->whereIn('status', [
                    'pending',
                    'paid',
                    'checked_in'
                ])
                ->get();

            $isAvailable = true;

            foreach ($reservasis as $reservasi) {

                $overlap = array_intersect(
                    $blockedSlots,
                    $reservasi->blocked_slots
                );

                if (!empty($overlap)) {

                    $isAvailable = false;

                    break;
                }
            }

            $availableMejas[] = [

                'id' => $meja->_id,

                'nomor_meja' =>
                $meja->nomor_meja,

                'kapasitas' =>
                $meja->kapasitas,

                'is_available' =>
                $isAvailable,
            ];
        }

        return response()->json([
            'success' => true,
            'message' => 'List meja',
            'data' => $availableMejas
        ]);
    }
}
