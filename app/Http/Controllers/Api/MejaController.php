<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Meja;
use Illuminate\Http\Request;


class MejaController extends Controller
{
    public function index()
    {
        $mejas = Meja::latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'List meja',
            'data' => $mejas
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->auth_user;

        $request->validate([
            'nomor_meja' => 'required',
            'kapasitas' => 'required|integer|min:2',
        ]);

        $exists = Meja::where(
            'cabang_id',
            $user->cabang_id
        )
            ->where(
                'nomor_meja',
                $request->nomor_meja
            )
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Nomor meja sudah digunakan'
            ], 422);
        }

        $meja = Meja::create([
            'cabang_id' => $user->cabang_id,
            'nomor_meja' => $request->nomor_meja,
            'kapasitas' => $request->kapasitas,
            'status' => 'active',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Meja berhasil dibuat',
            'data' => $meja
        ]);
    }

    public function myCabangMejas(Request $request)
    {
        $user = $request->auth_user;

        $mejas = Meja::where(
            'cabang_id',
            $user->cabang_id
        )
            ->orderBy('nomor_meja')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $mejas
        ]);
    }

    public function update(
        Request $request,
        string $id
    ) {
        $user = $request->auth_user;

        $meja = Meja::where(
            '_id',
            $id
        )
            ->where(
                'cabang_id',
                $user->cabang_id
            )
            ->first();

        if (!$meja) {

            return response()->json([
                'success' => false,
                'message' => 'Meja tidak ditemukan'
            ], 404);
        }

        $request->validate([
            'nomor_meja' => 'required',
            'kapasitas' => 'required|integer|min:2'
        ]);

        $exists = Meja::where(
            'cabang_id',
            $user->cabang_id
        )
            ->where(
                'nomor_meja',
                $request->nomor_meja
            )
            ->where(
                '_id',
                '!=',
                $meja->_id
            )
            ->exists();

        if ($exists) {

            return response()->json([
                'success' => false,
                'message' => 'Nomor meja sudah digunakan'
            ], 422);
        }

        $meja->update([
            'nomor_meja' => $request->nomor_meja,
            'kapasitas' => $request->kapasitas
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Meja berhasil diperbarui',
            'data' => $meja
        ]);
    }

    public function maintenance(
        Request $request,
        string $id
    ) {
        $user = $request->auth_user;

        $meja = Meja::where(
            '_id',
            $id
        )
            ->where(
                'cabang_id',
                $user->cabang_id
            )
            ->first();

        if (!$meja) {

            return response()->json([
                'success' => false,
                'message' => 'Meja tidak ditemukan'
            ], 404);
        }

        $meja->status = 'maintenance';

        $meja->save();

        return response()->json([
            'success' => true,
            'message' => 'Meja masuk maintenance'
        ]);
    }

    public function activate(
        Request $request,
        string $id
    ) {
        $user = $request->auth_user;

        $meja = Meja::where(
            '_id',
            $id
        )
            ->where(
                'cabang_id',
                $user->cabang_id
            )
            ->first();

        if (!$meja) {

            return response()->json([
                'success' => false,
                'message' => 'Meja tidak ditemukan'
            ], 404);
        }

        $meja->status = 'active';

        $meja->save();

        return response()->json([
            'success' => true,
            'message' => 'Meja aktif kembali'
        ]);
    }
}
