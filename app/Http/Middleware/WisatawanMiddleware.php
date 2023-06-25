<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use App\Models\User;
use Closure;

class WisatawanMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('token') ?? $request->query('token');
        $user = User::where('token', $token)->first();
        if (!$user) {
            return response()->json([
              'error' => 'Token not valid.'
            ], 401);
          }
        $id_role_user = $user->id_role_user;
        if ($id_role_user != '3') {
            return response()->json([
                'success' => false,
                'message' => 'Access denied. Only wisatawan allowed.'
            ], 403);
        }

        return $next($request);
    }
}
