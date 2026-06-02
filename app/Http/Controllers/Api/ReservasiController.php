<?php

namespace App\Http\Controllers\Api;

use App\Helpers\SlotHelper;
use App\Http\Controllers\Controller;
use App\Models\Meja;
use App\Models\Reservasi;
use Illuminate\Http\Request;
use App\Models\Cabang;
use Carbon\Carbon;

class ReservasiController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([

            'cabang_id' => 'required',

            'meja_id' => 'required',

            'tanggal_booking' => 'required|date|after_or_equal:today',

            'jam_mulai' => 'required',

            'durasi' => 'required|numeric|min:1|max:4',

            'catatan' => 'nullable|string',
        ]);

        $user = $request->auth_user;

        $meja = Meja::find($request->meja_id);

        if (!$meja) {

            return response()->json([
                'success' => false,
                'message' => 'Meja tidak ditemukan'
            ], 404);
        }

        if (
            (string)$meja->cabang_id !==
            (string)$request->cabang_id
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Meja tidak sesuai cabang'
            ], 422);
        }

        $cabang = $meja->cabang;

        if (
            $cabang->status !== 'active'
        ) {

            return response()->json([
                'success' => false,
                'message' => 'Cabang dinonaktifkan, tidak menerima reservasi'
            ], 422);
        }

        $jamBuka = Carbon::createFromFormat(
            'H:i',
            $cabang->jam_buka
        );

        $jamMulai = Carbon::createFromFormat(
            'H:i',
            $request->jam_mulai
        );

        $jamTutup = Carbon::createFromFormat(
            'H:i',
            $cabang->jam_tutup
        );

        $jamSelesai = $jamMulai
            ->copy()
            ->addHours($request->durasi);

        if (
            $jamMulai->lt($jamBuka)
            ||
            $jamSelesai->gt($jamTutup)
        ) {

            return response()->json([
                'success' => false,
                'message' => 'Reservasi di luar jam operasional cabang'
            ], 422);
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

            'tanggal_booking' => 'required|date|after_or_equal:today',

            'jam_mulai' => 'required',

            'durasi' => 'required|numeric|min:1|max:4',

            'kapasitas' => 'required|numeric|min:1',
        ]);

        $cabang = Cabang::find(
            $request->cabang_id
        );

        if (
            $cabang->status !== 'active'
        ) {

            return response()->json([
                'success' => false,
                'message' => 'Cabang dinonaktifkan'
            ], 422);
        }

        $jamBuka = Carbon::createFromFormat(
            'H:i',
            $cabang->jam_buka
        );

        $jamMulai = Carbon::createFromFormat(
            'H:i',
            $request->jam_mulai
        );

        $jamTutup = Carbon::createFromFormat(
            'H:i',
            $cabang->jam_tutup
        );

        $jamSelesai = $jamMulai
            ->copy()
            ->addHours($request->durasi);

        if (
            $jamMulai->lt($jamBuka)
            ||
            $jamSelesai->gt($jamTutup)
        ) {

            return response()->json([
                'success' => false,
                'message' => 'Reservasi di luar jam operasional cabang'
            ], 422);
        }

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

    public function checkIn(Request $request)
    {
        $request->validate([
            'kode_reservasi' => 'required'
        ]);

        $reservasi = Reservasi::where(
            'kode_reservasi',
            $request->kode_reservasi
        )->first();

        if (!$reservasi) {

            return response()->json([
                'success' => false,
                'message' => 'Reservasi tidak ditemukan'
            ], 404);
        }

        if (!in_array(
            $reservasi->status,
            ['pending', 'paid']
        )) {

            return response()->json([
                'success' => false,
                'message' => 'Reservasi tidak dapat check in'
            ], 422);
        }

        $reservasi->status = 'checked_in';

        $reservasi->save();

        return response()->json([
            'success' => true,
            'message' => 'Check in berhasil',
            'data' => $reservasi
        ]);
    }

    public function complete(Request $request)
    {
        $request->validate([
            'reservasi_id' => 'required'
        ]);

        $reservasi = Reservasi::find(
            $request->reservasi_id
        );

        if (!$reservasi) {

            return response()->json([
                'success' => false,
                'message' => 'Reservasi tidak ditemukan'
            ], 404);
        }

        if ($reservasi->status !== 'checked_in') {

            return response()->json([
                'success' => false,
                'message' => 'Reservasi belum check in'
            ], 422);
        }

        $reservasi->status = 'completed';

        $reservasi->save();

        return response()->json([
            'success' => true,
            'message' => 'Reservasi selesai',
            'data' => $reservasi
        ]);
    }

    public function cancel(Request $request)
    {
        $request->validate([
            'reservasi_id' => 'required'
        ]);

        $reservasi = Reservasi::find(
            $request->reservasi_id
        );

        if (!$reservasi) {

            return response()->json([
                'success' => false,
                'message' => 'Reservasi tidak ditemukan'
            ], 404);
        }

        if (
            $reservasi->status === 'completed'
        ) {

            return response()->json([
                'success' => false,
                'message' => 'Reservasi sudah selesai'
            ], 422);
        }

        $reservasi->status = 'cancelled';

        $reservasi->save();

        return response()->json([
            'success' => true,
            'message' => 'Reservasi dibatalkan',
            'data' => $reservasi
        ]);
    }

    public function detailReservation(
        Request $request,
        string $id
    ) {
        $user = $request->auth_user;

        $reservasi = Reservasi::where(
            '_id',
            $id
        )
            ->where(
                'user_id',
                $user->_id
            )
            ->first();

        if (!$reservasi) {

            return response()->json([
                'success' => false,
                'message' => 'Reservasi tidak ditemukan'
            ], 404);
        }

        $meja = Meja::find(
            $reservasi->meja_id
        );

        $cabang = Cabang::find(
            $reservasi->cabang_id
        );

        return response()->json([
            'success' => true,
            'message' => 'Detail reservasi',
            'data' => [
                'reservasi' => $reservasi,
                'meja' => $meja,
                'cabang' => $cabang
            ]
        ]);
    }

    public function cancelReservation(
        Request $request,
        string $id
    ) {
        $user = $request->auth_user;

        $reservasi = Reservasi::where(
            '_id',
            $id
        )
            ->where(
                'user_id',
                $user->_id
            )
            ->first();

        if (!$reservasi) {

            return response()->json([
                'success' => false,
                'message' => 'Reservasi tidak ditemukan'
            ], 404);
        }

        if (!in_array(
            $reservasi->status,
            ['pending', 'paid']
        )) {

            return response()->json([
                'success' => false,
                'message' => 'Reservasi tidak dapat dibatalkan'
            ], 422);
        }

        $reservasi->status = 'cancelled';

        $reservasi->save();

        return response()->json([
            'success' => true,
            'message' => 'Reservasi berhasil dibatalkan'
        ]);
    }

    public function listReservasiCabang(Request $request)
    {
        $user = $request->auth_user;

        $reservasis = Reservasi::where(
            'cabang_id',
            $user->cabang_id
        )
            ->orderBy(
                'tanggal_booking',
                'desc'
            )
            ->get();

        return response()->json([
            'success' => true,
            'data' => $reservasis
        ]);
    }

    public function detailReservasiCabang(
        Request $request,
        string $id
    ) {
        $user = $request->auth_user;

        $reservasi = Reservasi::where(
            '_id',
            $id
        )
            ->where(
                'cabang_id',
                $user->cabang_id
            )
            ->first();

        if (!$reservasi) {

            return response()->json([
                'success' => false,
                'message' => 'Reservasi tidak ditemukan'
            ], 404);
        }

        $meja = Meja::find(
            $reservasi->meja_id
        );

        return response()->json([
            'success' => true,
            'data' => [
                'reservasi' => $reservasi,
                'meja' => $meja
            ]
        ]);
    }

    public function reservasiHariIni(
        Request $request
    ) {
        $user = $request->auth_user;

        $today = now()->format('Y-m-d');

        $reservasis = Reservasi::where(
            'cabang_id',
            $user->cabang_id
        )
            ->where(
                'tanggal_booking',
                $today
            )
            ->orderBy('jam_mulai', 'asc')

            ->get();

        return response()->json([
            'success' => true,
            'data' => $reservasis
        ]);
    }

    public function availableJam(Request $request)
    {
        $request->validate([
            'cabang_id' => 'required',
            'durasi' => 'required|numeric|min:1|max:4',
        ]);

        $cabang = Cabang::find(
            $request->cabang_id
        );

        if (!$cabang) {

            return response()->json([
                'success' => false,
                'message' => 'Cabang tidak ditemukan'
            ], 404);
        }

        if (
            $cabang->status !== 'active'
        ) {

            return response()->json([
                'success' => false,
                'message' => 'Cabang dinonaktifkan'
            ], 422);
        }

        $jamBuka = \Carbon\Carbon::createFromFormat(
            'H:i',
            $cabang->jam_buka
        );

        $jamTutup = \Carbon\Carbon::createFromFormat(
            'H:i',
            $cabang->jam_tutup
        );

        $jamTerakhir = $jamTutup
            ->copy()
            ->subHours(
                (int) $request->durasi
            );

        $jamTersedia = [];

        while (
            $jamBuka->lte($jamTerakhir)
        ) {

            $jamTersedia[] =
                $jamBuka->format('H:i');

            $jamBuka->addMinutes(30);
        }

        return response()->json([
            'success' => true,
            'message' => 'List jam tersedia',
            'data' => $jamTersedia
        ]);
    }
}
