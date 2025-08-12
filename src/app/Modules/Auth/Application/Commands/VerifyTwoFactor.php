<?php
namespace App\Modules\Auth\Application\Commands;

use App\Modules\Auth\Application\DTO\TwoFactorVerifyData;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class VerifyTwoFactor
{
    public function handle(TwoFactorVerifyData $data): void
    {
        $user = Auth::guard('admin')->user();
        abort_if(!$user, 401);

        $key = "admin_2fa_code_{$user->id}";
        $expected = Cache::get($key);

        if ($expected !== $data->code) {
            abort(422, 'Invalid 2FA code.');
        }

        Cache::forget($key);
        session(['admin_needs_2fa' => false, 'admin_2fa_passed' => true]);
        $user->two_factor_passed_at = now();
        $user->save();
    }
}
