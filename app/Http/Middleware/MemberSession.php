<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class MemberSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request)  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('member')) {
            return redirect()->route('showListBuku');
        }

        return $next($request);
    }
}
