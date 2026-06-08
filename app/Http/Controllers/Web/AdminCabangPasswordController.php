<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminCabangPasswordController extends Controller
{
    public function edit()
    {
        return view('admin-cabang.password.edit');
    }

    public function update(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()
                ->withErrors([
                    'current_password' => 'Password lama tidak sesuai',
                ])
                ->withInput();
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with(
            'success',
            'Password berhasil diganti'
        );
    }
}
