<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class ApiAuthMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $bearerToken = $request->bearerToken();

        if (!$bearerToken) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak ditemukan'
            ], 401);
        }

        $hashedToken = hash('sha256', $bearerToken);

        $user = User::whereHas('tokens', function ($query) use ($hashedToken) {
            $query->where('token', $hashedToken);
        })->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $request->merge([
            'auth_user' => $user
        ]);

        return $next($request);
    }
}