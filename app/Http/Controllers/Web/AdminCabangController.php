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

        return view(
            'admin-master.admin-cabang.index',
            compact('admins')
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
}
