<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        //Cek Login
        if (!($request->session()->has('login_id') || Auth::check())) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}
