<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Auth\RequestGuard;

class Authorization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = $request->header('token') ?? $request->query('token');
        if (!$token) {
            return response()->json([
                'status' => 'Error',
                'message' => 'token not provided',
            ],400);
        }

        $user = User::where('token', $token)->first();
        if (!$user) {
            return response()->json([
                'status' => 'Error',
                'message' => 'invalid token',
            ],400);
        }
        $request->user = $user;
        return $next($request);
    }
}