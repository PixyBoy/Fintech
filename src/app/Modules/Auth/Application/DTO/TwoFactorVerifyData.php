<?php
namespace App\Modules\Auth\Application\DTO;

class TwoFactorVerifyData {
    public function __construct(public string $code) {}
}
