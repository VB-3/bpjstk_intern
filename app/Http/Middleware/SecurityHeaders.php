<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request and apply strict HTTP security headers.
     * Mencegah clickjacking, MIME-sniffing, XSS, dan kebocoran informasi server.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Hapus header fingerprint server yang membocorkan versi PHP / teknologi
        if (function_exists('header_remove')) {
            header_remove('X-Powered-By');
            header_remove('Server');
        }

        $headers = [
            // Mencegah website dimasukkan ke iframe situs lain (Clickjacking protection)
            'X-Frame-Options' => 'SAMEORIGIN',
            // Mencegah browser menebak MIME-type yang berpotensi mengeksekusi script jahat
            'X-Content-Type-Options' => 'nosniff',
            // Proteksi filter XSS bawaan browser
            'X-XSS-Protection' => '1; mode=block',
            // Mencegah kebocoran URL internal melalui header Referer
            'Referrer-Policy' => 'strict-origin-when-cross-origin',
            // Batasi izin API browser: izinkan kamera untuk absensi, matikan mikrofon & sensor lain
            'Permissions-Policy' => 'camera=(self), microphone=(), geolocation=(), payment=()',
        ];

        foreach ($headers as $key => $value) {
            $response->headers->set($key, $value);
        }

        return $response;
    }
}
