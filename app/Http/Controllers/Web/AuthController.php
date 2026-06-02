<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function loginPage()
    {
        return view(
            'auth.login'
        );
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($credentials)) {

            return back()->withErrors([
                'email' => 'Email atau password salah'
            ]);
        }

        $request->session()->regenerate();

        $user = Auth::user();

        if (
            $user->status !== 'active'
        ) {

            Auth::logout();

            return back()->withErrors([
                'email' => 'Akun tidak aktif'
            ]);
        }

        if (
            $user->role === 'admin_master'
        ) {

            return redirect(
                '/dashboard'
            );
        }

        if (
            $user->role === 'admin_cabang'
        ) {

            return redirect(
                '/admin-cabang/dashboard'
            );
        }

        Auth::logout();

        return back();
    }

    public function logout(
        Request $request
    ) {
        Auth::logout();

        $request
            ->session()
            ->invalidate();

        $request
            ->session()
            ->regenerateToken();

        return redirect(
            '/admin/login'
        );
    }
}