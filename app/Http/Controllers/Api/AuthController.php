<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PersonalAccessToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|numeric',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => bcrypt($request->password),
            'role' => 'customer',
        ]);

        // Manual token generation
        $plainTextToken = Str::random(40);
        $token = hash('sha256', $plainTextToken);

        PersonalAccessToken::create([
            'tokenable_id' => $user->_id,
            'tokenable_type' => User::class,
            'name' => 'customer_token',
            'token' => $token,
            'abilities' => ['*'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Register berhasil',
            'data' => [
                'token' => $plainTextToken,
                'user' => $user
            ]
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Email tidak ditemukan'
            ], 401);
        }

        if ($user->role !== 'customer') {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak'
            ], 403);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password salah'
            ], 401);
        }

        $plainTextToken = Str::random(40);
        $token = hash('sha256', $plainTextToken);

        PersonalAccessToken::create([
            'tokenable_id' => $user->_id,
            'tokenable_type' => User::class,
            'name' => 'customer_token',
            'token' => $token,
            'abilities' => ['*'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'data' => [
                'token' => $plainTextToken,
                'user' => $user
            ]
        ]);
    }

    public function profile(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Success',
            'data' => $request->auth_user
        ]);
    }

    public function logout(Request $request)
    {
        $token = $request->bearerToken();

        PersonalAccessToken::where(
            'token',
            hash('sha256', $token)
        )->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil'
        ]);
    }
}
