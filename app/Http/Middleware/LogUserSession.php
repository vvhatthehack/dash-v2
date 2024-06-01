<?php

// app/Http/Middleware/LogUserSession.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SessionHistory;

class LogUserSession
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $session = SessionHistory::firstOrNew([
                'user_id' => Auth::id(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'is_active' => true,
            ]);

            $session->last_activity = now();
            $session->save();
        }

        return $next($request);
    }
}
