<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureWhatsappIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && !$request->user()->hasVerifiedWhatsapp()) {
            return redirect()->route('verification.whatsapp')->withErrors([
                'whatsapp' => 'You need to verify your WhatsApp to access this section.'
            ]);
        }

        return $next($request);
    }
}
