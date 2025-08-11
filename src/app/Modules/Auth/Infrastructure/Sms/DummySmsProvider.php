<?php

namespace App\Modules\Auth\Infrastructure\Sms;

use App\Modules\Auth\Domain\Contracts\SmsProviderInterface;
use Illuminate\Support\Facades\Log;

class DummySmsProvider implements SmsProviderInterface
{
    public function sendCode(string $phone, string $code): void
    {
        $masked = substr($phone, 0, 4).'****'.substr($phone, -2);
        Log::info('SMS to '.$masked.' code: '.$code);
    }
}
