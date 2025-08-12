<?php
namespace App\Modules\Auth\Application\Jobs;

use App\Modules\Auth\Infrastructure\Integrations\SmsGateway;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendOtpSms implements ShouldQueue
{
    use Queueable;

    public function __construct(public string $phone, public string $code) {}

    public function handle(SmsGateway $sms): void
    {
        $sms->send($this->phone, "Your verification code: {$this->code}");
    }
}
