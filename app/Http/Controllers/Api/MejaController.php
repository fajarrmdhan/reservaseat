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
        $request->validate([
            'cabang_id' => 'required',
            'nomor_meja' => 'required',
            'kapasitas' => 'required|numeric',
        ]);

        $exists = Meja::where('cabang_id', $request->cabang_id)
            ->where('nomor_meja', $request->nomor_meja)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Nomor meja sudah digunakan'
            ], 422);
        }

        $meja = Meja::create([
            'cabang_id' => $request->cabang_id,
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
}