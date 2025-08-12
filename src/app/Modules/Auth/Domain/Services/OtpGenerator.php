<?php
namespace App\Modules\Auth\Domain\Services;

class OtpGenerator {
    public function generate(int $digits = 6): string
    {
        return str_pad((string)random_int(0, (10**$digits)-1), $digits, '0', STR_PAD_LEFT);
    }
}
