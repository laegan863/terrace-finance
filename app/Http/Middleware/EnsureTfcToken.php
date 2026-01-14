<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTfcToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $token = session('tfc.token');
        $expiresAt = session('tfc.expires_at');

        // Not logged in
        if (!$token || !$expiresAt) {
            return redirect()->route('tfc.login')
                ->with('auth_error', 'Please login to continue.');
        }

        // Expired token (token expires after 30 minutes) :contentReference[oaicite:2]{index=2}
        if (now()->greaterThanOrEqualTo(Carbon::parse($expiresAt))) {
            $this->forceLogout($request);

            return redirect()->route('tfc.login')
                ->with('auth_error', 'Your token has expired. Please login again.');
        }

        return $next($request);
    }

    private function forceLogout(Request $request): void
    {
        $request->session()->forget(['tfc.username', 'tfc.token', 'tfc.expires_at']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }

}
