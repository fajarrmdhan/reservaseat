<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Cabang;
use Illuminate\Http\Request;

class CabangController extends Controller
{
    public function index()
    {
        $cabangs = Cabang::all();

        return view(
            'admin-master.cabang.index',
            compact('cabangs')
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_cabang' => 'required',
            'alamat' => 'required',
            'jam_buka' => 'required',
            'jam_tutup' => 'required',
        ]);

        Cabang::create([
            'nama_cabang' => $request->nama_cabang,
            'alamat' => $request->alamat,
            'jam_buka' => $request->jam_buka,
            'jam_tutup' => $request->jam_tutup,
            'foto_cabang' => null,
            'denah_cabang' => null,
            'status' => 'active'
        ]);

        return redirect()
            ->back()
            ->with(
                'success',
                'Cabang berhasil ditambahkan'
            );
    }

    public function update(
        Request $request,
        string $id
    ) {
        $cabang = Cabang::find($id);

        if (!$cabang) {
            return back();
        }

        $request->validate([
            'nama_cabang' => 'required',
            'alamat' => 'required',
            'jam_buka' => 'required',
            'jam_tutup' => 'required'
        ]);

        $cabang->update([
            'nama_cabang' => $request->nama_cabang,
            'alamat' => $request->alamat,
            'jam_buka' => $request->jam_buka,
            'jam_tutup' => $request->jam_tutup,
        ]);

        return back()->with(
            'success',
            'Cabang berhasil diperbarui'
        );
    }

    public function activate(string $id)
    {
        $cabang = Cabang::find($id);

        if (!$cabang) {
            return back();
        }

        $cabang->status = 'active';

        $cabang->save();

        return back()->with(
            'success',
            'Cabang berhasil diaktifkan'
        );
    }

    public function deactivate(string $id)
    {
        $cabang = Cabang::find($id);

        if (!$cabang) {
            return back();
        }

        $cabang->status = 'inactive';

        $cabang->save();

        return back()->with(
            'success',
            'Cabang berhasil dinonaktifkan'
        );
    }
}
