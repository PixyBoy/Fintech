<?php

namespace App\Modules\Auth\Application\Services;

use App\Modules\Auth\Domain\Contracts\OtpStoreInterface;
use App\Modules\Auth\Application\Jobs\SendOtpSms;
use Illuminate\Support\Facades\Bus;
use Illuminate\Validation\ValidationException;

class OtpService
{
    public function __construct(private OtpStoreInterface $store)
    {
    }

    private function normalize(string $phone): ?string
    {
        $digits = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($digits, '98')) {
            $digits = substr($digits, 2);
        }
        if (strlen($digits) === 10 && $digits[0] === '9') {
            return '0'.$digits;
        }
        if (strlen($digits) === 11 && str_starts_with($digits, '09')) {
            return $digits;
        }
        return null;
    }

    public function requestCode(string $phone, string $ip): void
    {
        $phone = $this->normalize($phone);
        if (!$phone) {
            throw ValidationException::withMessages(['phone' => ['invalid phone']]);
        }
        if (!$this->store->canSend($phone, $ip)) {
            abort(429, 'Too Many Requests');
        }
        $code = (string)random_int(100000, 999999);
        $hash = hash('sha256', $code.config('app.key'));
        $this->store->put($phone, $hash, 300);
        $this->store->markSent($phone, $ip);
        Bus::dispatch((new SendOtpSms($phone, $code))->onQueue('high'));
    }

    public function verifyCode(string $phone, string $code, string $ip): bool
    {
        $phone = $this->normalize($phone);
        if (!$phone) {
            throw ValidationException::withMessages(['phone' => ['invalid phone']]);
        }
        $record = $this->store->get($phone);
        if (!$record) {
            throw ValidationException::withMessages(['code' => ['not found']]);
        }
        if ($record['locked_until']) {
            throw ValidationException::withMessages(['phone' => ['locked']]);
        }
        $hash = hash('sha256', $code.config('app.key'));
        if (!hash_equals($record['code'] ?? '', $hash)) {
            $attempts = $this->store->incrAttempts($phone);
            if ($attempts >= 5) {
                $this->store->lock($phone, 600);
            }
            throw ValidationException::withMessages(['code' => ['invalid']]);
        }
        $this->store->delete($phone);
        return true;
    }
}
