<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Cabang;

class AdminCabangController extends Controller
{
    public function index()
    {
        $admins = User::where(
            'role',
            'admin_cabang'
        )->get();

        $cabangs = Cabang::where(
            'status',
            'active'
        )->get();

        return view(
            'admin-master.admin-cabang.index',
            compact(
                'admins',
                'cabangs'
            )
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required',
            'cabang_id' => 'required'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => bcrypt('password123'),
            'photo_profile' => null,
            'role' => 'admin_cabang',
            'cabang_id' => $request->cabang_id,
            'status' => 'active'
        ]);

        return back()->with(
            'success',
            'Admin cabang berhasil ditambahkan'
        );
    }

    public function update(
        Request $request,
        string $id
    ) {
        $admin = User::find($id);

        if (!$admin) {
            return back();
        }

        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'cabang_id' => 'required'
        ]);

        $admin->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'cabang_id' => $request->cabang_id,
        ]);

        return back()->with(
            'success',
            'Admin cabang berhasil diperbarui'
        );
    }

    public function activate(string $id)
    {
        $admin = User::find($id);

        if (!$admin) {
            return back();
        }

        $admin->status = 'active';

        $admin->save();

        return back()->with(
            'success',
            'Admin cabang berhasil diaktifkan'
        );
    }

    public function deactivate(string $id)
    {
        $admin = User::find($id);

        if (!$admin) {
            return back();
        }

        $admin->status = 'inactive';

        $admin->save();

        return back()->with(
            'success',
            'Admin cabang berhasil dinonaktifkan'
        );
    }

    public function resetPassword(string $id)
    {
        $admin = User::find($id);

        if (!$admin) {
            return back();
        }

        $admin->password = bcrypt(
            'password123'
        );

        $admin->save();

        return back()->with(
            'success',
            'Password admin berhasil direset menjadi password123'
        );
    }
}
