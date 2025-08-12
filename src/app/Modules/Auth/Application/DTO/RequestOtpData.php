<?php
namespace App\Modules\Auth\Application\DTO;

class RequestOtpData {
    public function __construct(public string $phone) {}
}
