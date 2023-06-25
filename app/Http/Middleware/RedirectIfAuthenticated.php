<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate;

class RedirectIfAuthenticated extends Authenticate
{
    public function handle($request, Closure $next, ...$guards)
    {
        if ($this->auth->guard($guards)->check()) {
            return redirect('/home'); // Ganti dengan rute yang sesuai
        }

        return $next($request);
    }
}
