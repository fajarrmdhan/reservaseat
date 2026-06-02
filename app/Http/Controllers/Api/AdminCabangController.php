<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminCabangController extends Controller
{
    public function index()
    {
        $admins = User::where(
            'role',
            'admin_cabang'
        )->get();

        return response()->json([
            'success' => true,
            'message' => 'List admin cabang',
            'data' => $admins
        ]);
    }

    public function show(string $id)
    {
        $admin = User::where(
            '_id',
            $id
        )
        ->where(
            'role',
            'admin_cabang'
        )
        ->first();

        if (!$admin) {
            return response()->json([
                'success' => false,
                'message' => 'Admin cabang tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $admin
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required',
            'password' => 'required|min:8',
            'cabang_id' => 'required'
        ]);

        $admin = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => bcrypt($request->password),
            'photo_profile' => null,
            'role' => 'admin_cabang',
            'cabang_id' => $request->cabang_id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Admin cabang berhasil dibuat',
            'data' => $admin
        ]);
    }

    public function update(
        Request $request,
        string $id
    ) {
        $admin = User::where(
            '_id',
            $id
        )
        ->where(
            'role',
            'admin_cabang'
        )
        ->first();

        if (!$admin) {
            return response()->json([
                'success' => false,
                'message' => 'Admin cabang tidak ditemukan'
            ], 404);
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

        return response()->json([
            'success' => true,
            'message' => 'Admin cabang berhasil diperbarui',
            'data' => $admin
        ]);
    }
}