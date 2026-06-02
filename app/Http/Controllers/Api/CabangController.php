<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cabang;
use Illuminate\Http\Request;

class CabangController extends Controller
{
    public function index()
    {
        $cabangs = Cabang::orderBy(
            'created_at',
            'desc'
        )->get();

        return response()->json([
            'success' => true,
            'message' => 'List cabang',
            'data' => $cabangs
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_cabang' => 'required',
            'alamat' => 'required',
            'jam_buka' => 'required',
            'jam_tutup' => 'required',

            'foto_cabang' => 'nullable|image',

            'denah_cabang' => 'nullable|image',
        ]);

        $fotoCabang = null;

        if ($request->hasFile('foto_cabang')) {

            $file = $request->file('foto_cabang');

            $filename = time() . '_' . $file->getClientOriginalName();

            $file->storeAs('cabangs', $filename, 'public');

            $fotoCabang = asset('storage/cabangs/' . $filename);
        }

        $denahCabang = null;

        if ($request->hasFile('denah_cabang')) {

            $file = $request->file('denah_cabang');

            $filename = time() . '_' . $file->getClientOriginalName();

            $file->storeAs('denah', $filename, 'public');

            $denahCabang = asset('storage/denah/' . $filename);
        }

        $cabang = Cabang::create([
            'nama_cabang' => $request->nama_cabang,

            'alamat' => $request->alamat,

            'jam_buka' => $request->jam_buka,

            'jam_tutup' => $request->jam_tutup,

            'foto_cabang' => $fotoCabang,

            'denah_cabang' => $denahCabang,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cabang berhasil dibuat',
            'data' => $cabang
        ]);
    }

    public function show(string $id)
    {
        $cabang = Cabang::find($id);

        if (!$cabang) {
            return response()->json([
                'success' => false,
                'message' => 'Cabang tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $cabang
        ]);
    }

    public function update(
        Request $request,
        string $id
    ) {
        $cabang = Cabang::find($id);

        if (!$cabang) {
            return response()->json([
                'success' => false,
                'message' => 'Cabang tidak ditemukan'
            ], 404);
        }

        $request->validate([
            'nama_cabang' => 'required',
            'alamat' => 'required',
            'jam_buka' => 'required',
            'jam_tutup' => 'required',
            'foto_cabang' => 'nullable',
            'denah_cabang' => 'nullable',
        ]);

        $cabang->update([
            'nama_cabang' => $request->nama_cabang,
            'alamat' => $request->alamat,
            'jam_buka' => $request->jam_buka,
            'jam_tutup' => $request->jam_tutup,
            'foto_cabang' => $request->foto_cabang,
            'denah_cabang' => $request->denah_cabang,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cabang berhasil diperbarui',
            'data' => $cabang
        ]);
    }
}
