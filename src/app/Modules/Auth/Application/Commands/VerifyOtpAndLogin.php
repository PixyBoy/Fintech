<?php
namespace App\Modules\Auth\Application\Commands;

use App\Modules\Auth\Application\DTO\VerifyOtpData;
use App\Modules\Auth\Domain\Repositories\UserRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class VerifyOtpAndLogin
{
    public function __construct(private UserRepository $users) {}

    public function handle(VerifyOtpData $data): void
    {
        $phone = preg_replace('/\D+/', '', $data->phone);

        $row = DB::table('phone_otps')
            ->where('phone', $phone)
            ->latest('id')
            ->first();

        if (! $row) abort(422, 'OTP not requested.');
        if (Carbon::parse($row->expires_at)->isPast()) abort(422, 'OTP expired.');

        // افزایش attempts
        DB::table('phone_otps')->where('id', $row->id)->increment('attempts');

        if (! Hash::check($data->code, $row->code_hash)) {
            abort(422, 'Invalid code.');
        }

        // پیدا کن یا بساز
        $user = $this->users->findByPhone($phone) ?? $this->users->createWithPhone($phone);

        // بروزرسانی phone_verified_at
        if (is_null($user->phone_verified_at)) {
            $user->phone_verified_at = now();
            $user->save();
        }

        // لاگین با guard web
        Auth::guard('web')->login($user, true);
    }
}
