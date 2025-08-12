<?php
namespace App\Modules\Auth\Application\Commands;

use App\Modules\Auth\Application\DTO\AdminLoginData;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class AdminLogin
{
    public function handle(AdminLoginData $data): bool
    {
        if (! Auth::guard('admin')->attempt(['email' => $data->email, 'password' => $data->password], $data->remember)) {
            abort(422, 'Invalid credentials.');
        }

        $user = Auth::guard('admin')->user();
        if (! $user->is_admin) {
            Auth::guard('admin')->logout();
            abort(403, 'Not an admin.');
        }

        if ($user->two_factor_enabled) {
            // تولید کد 2FA موقت و لاگ (برای PoC)
            $code = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            Cache::put("admin_2fa_code_{$user->id}", $code, now()->addMinutes(5));
            logger()->info("[ADMIN-2FA] user={$user->id} code={$code}");
            session(['admin_needs_2fa' => true]);
            return true; // نیاز به 2FA
        }

        session(['admin_needs_2fa' => false, 'admin_2fa_passed' => true]);
        return false; // بی‌نیاز از 2FA
    }
}
