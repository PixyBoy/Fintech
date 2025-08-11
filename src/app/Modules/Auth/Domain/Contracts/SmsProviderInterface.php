<?php

namespace App\Modules\Auth\Domain\Contracts;

interface SmsProviderInterface
{
    public function sendCode(string $phone, string $code): void;
}
