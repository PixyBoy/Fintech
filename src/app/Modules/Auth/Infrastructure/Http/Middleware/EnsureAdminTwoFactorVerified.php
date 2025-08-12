<?php
namespace App\Modules\Auth\Infrastructure\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminTwoFactorVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth('admin')->check()) {
            if (session('admin_needs_2fa', false) || ! session('admin_2fa_passed', false)) {
                return redirect()->route('auth.admin.2fa');
            }
        }
        return $next($request);
    }
}
