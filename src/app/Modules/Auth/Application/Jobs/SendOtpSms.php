<?php

namespace App\Modules\Auth\Application\Jobs;

use App\Modules\Auth\Domain\Contracts\SmsProviderInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendOtpSms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public string $phone, public string $code)
    {
    }

    public function handle(SmsProviderInterface $provider): void
    {
        $provider->sendCode($this->phone, $this->code);
    }
}
