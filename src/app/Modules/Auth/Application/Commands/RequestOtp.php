<?php
namespace App\Modules\Auth\Application\Commands;

use App\Modules\Auth\Application\DTO\RequestOtpData;
use App\Modules\Auth\Application\Jobs\SendOtpSms;
use App\Modules\Auth\Domain\Services\OtpGenerator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Bus;

class RequestOtp
{
    public function handle(RequestOtpData $data): void
    {
        $phone = preg_replace('/\D+/', '', $data->phone);

        // rate limit ساده: اجازه ارسال هر 60 ثانیه
        $now = now();
        $exists = DB::table('phone_otps')
            ->where('phone', $phone)
            ->latest('id')
            ->first();
        if ($exists && $exists->last_sent_at && Carbon::parse($exists->last_sent_at)->diffInSeconds($now) < 60) {
            return; // در عمل باید پیغام مناسب بدهید
        }

        $code = app(OtpGenerator::class)->generate(6);
        DB::table('phone_otps')->insert([
            'phone' => $phone,
            'code_hash' => Hash::make($code),
            'expires_at' => $now->copy()->addMinutes(5),
            'attempts' => 0,
            'last_sent_at' => $now,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        Bus::dispatch(new SendOtpSms($phone, $code));
    }
}
