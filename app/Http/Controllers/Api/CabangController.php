<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cabang;
use Illuminate\Http\Request;
use App\Models\User;

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

            'status' => 'active',
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
            'nama_cabang' => 'sometimes|required',
            'alamat' => 'sometimes|required',
            'jam_buka' => 'sometimes|required',
            'jam_tutup' => 'sometimes|required',

            'foto_cabang' => 'sometimes|image',
            'denah_cabang' => 'sometimes|image',
        ]);

        $data = [];

        if ($request->has('nama_cabang')) {
            $data['nama_cabang'] =
                $request->nama_cabang;
        }

        if ($request->has('alamat')) {
            $data['alamat'] =
                $request->alamat;
        }

        if ($request->has('jam_buka')) {
            $data['jam_buka'] =
                $request->jam_buka;
        }

        if ($request->has('jam_tutup')) {
            $data['jam_tutup'] =
                $request->jam_tutup;
        }

        if ($request->hasFile('foto_cabang')) {

            $file =
                $request->file('foto_cabang');

            $filename =
                time() . '_' .
                $file->getClientOriginalName();

            $file->storeAs(
                'cabangs',
                $filename,
                'public'
            );

            $data['foto_cabang'] =
                asset(
                    'storage/cabangs/' .
                        $filename
                );
        }

        if ($request->hasFile('denah_cabang')) {

            $file =
                $request->file('denah_cabang');

            $filename =
                time() . '_' .
                $file->getClientOriginalName();

            $file->storeAs(
                'denah',
                $filename,
                'public'
            );

            $data['denah_cabang'] =
                asset(
                    'storage/denah/' .
                        $filename
                );
        }

        if (empty($data)) {

            return response()->json([
                'success' => false,
                'message' => 'Tidak ada data yang diperbarui'
            ], 422);
        }

        $cabang->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Cabang berhasil diperbarui',
            'data' => $cabang
        ]);
    }

    public function deactivate(string $id)
    {
        $cabang = Cabang::find($id);

        if (!$cabang) {
            return response()->json([
                'success' => false,
                'message' => 'Cabang tidak ditemukan'
            ], 404);
        }

        $cabang->status = 'inactive';

        $cabang->save();

        User::where(
            'cabang_id',
            $cabang->_id
        )
            ->where(
                'role',
                'admin_cabang'
            )
            ->update([
                'status' => 'inactive'
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Cabang berhasil dinonaktifkan'
        ]);
    }

    public function activate(string $id)
    {
        $cabang = Cabang::find($id);

        if (!$cabang) {
            return response()->json([
                'success' => false,
                'message' => 'Cabang tidak ditemukan'
            ], 404);
        }

        $cabang->status = 'active';

        $cabang->save();

        User::where(
            'cabang_id',
            $cabang->_id
        )
            ->where(
                'role',
                'admin_cabang'
            )
            ->update([
                'status' => 'active'
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Cabang berhasil diaktifkan'
        ]);
    }
}
