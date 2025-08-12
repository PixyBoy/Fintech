<?php
namespace App\Modules\Auth\Infrastructure\Integrations;

use Illuminate\Support\Facades\Log;

class LogSmsGateway implements SmsGateway {
    public function send(string $phone, string $message): void
    {
        Log::info("[SMS] to {$phone}: {$message}");
    }
}
