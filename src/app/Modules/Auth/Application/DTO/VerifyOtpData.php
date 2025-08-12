<?php
namespace App\Modules\Auth\Application\DTO;

class VerifyOtpData {
    public function __construct(public string $phone, public string $code) {}
}
