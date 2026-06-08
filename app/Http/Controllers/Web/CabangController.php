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
            'foto_cabang' => 'nullable|image',
            'denah_cabang' => 'nullable|image',
        ]);

        $foto_cabang = null;
        if ($request->hasFile('foto_cabang')) {
            $file = $request->file('foto_cabang');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/cabangs'), $filename);
            $foto_cabang = 'uploads/cabangs/' . $filename;
        }

        $denah_cabang = null;
        if ($request->hasFile('denah_cabang')) {
            $file = $request->file('denah_cabang');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/denah'), $filename);
            $denah_cabang = 'uploads/denah/' . $filename;
        }

        Cabang::create([
            'nama_cabang' => $request->nama_cabang,
            'alamat' => $request->alamat,
            'jam_buka' => $request->jam_buka,
            'jam_tutup' => $request->jam_tutup,
            'foto_cabang' => $foto_cabang,
            'denah_cabang' => $denah_cabang,
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
            'jam_tutup' => 'required',
            'foto_cabang' => 'nullable|image',
            'denah_cabang' => 'nullable|image',
        ]);

        $data = [
            'nama_cabang' => $request->nama_cabang,
            'alamat' => $request->alamat,
            'jam_buka' => $request->jam_buka,
            'jam_tutup' => $request->jam_tutup,
        ];

        if ($request->hasFile('foto_cabang')) {
            $file = $request->file('foto_cabang');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/cabangs'), $filename);
            $data['foto_cabang'] = 'uploads/cabangs/' . $filename;
        }

        if ($request->hasFile('denah_cabang')) {
            $file = $request->file('denah_cabang');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/denah'), $filename);
            $data['denah_cabang'] = 'uploads/denah/' . $filename;
        }

        $cabang->update($data);

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
