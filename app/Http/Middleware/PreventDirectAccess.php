<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventDirectAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cek Referer Request (Sumber URL Sebelumnya) adalah dari halaman Anda
        $referer = $request->headers->get('referer');
        $allowedReferer = url('/dashboard'); // Akan Dikembalikan Langsung Menuju Dashboard

        // Cek 'allowed'
        if ($request->has('allowed') || $request->input('allowed') == 1) {
            // Cek CSRF token
            if ($this->isValidToken($request)) {
                return $next($request);
            } else {
                return $next($request);
            }
        }

        return redirect($allowedReferer);
    }

    private function isValidToken($request)
    {
        return $request->has('_token') && $request->session()->token() == $request->input('_token');
    }
}
