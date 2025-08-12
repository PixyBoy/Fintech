<?php
namespace App\Modules\Auth\Application\DTO;

class AdminLoginData {
    public function __construct(
        public string $email,
        public string $password,
        public bool $remember = true
    ) {}
}
