<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WebRoleMiddleware
{
    public function handle(
        Request $request,
        Closure $next,
        ...$roles
    ): Response {

        $user = $request->user();

        if (!$user) {
            return redirect('/admin/login');
        }

        if (!in_array($user->role, $roles)) {
            abort(403);
        }

        return $next($request);
    }
}