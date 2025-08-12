<?php
namespace App\Modules\Auth\Infrastructure\Integrations;

interface SmsGateway {
    public function send(string $phone, string $message): void;
}
