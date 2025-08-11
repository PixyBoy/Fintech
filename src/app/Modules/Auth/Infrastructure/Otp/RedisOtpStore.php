<?php

namespace App\Modules\Auth\Infrastructure\Otp;

use App\Modules\Auth\Domain\Contracts\OtpStoreInterface;
use Illuminate\Support\Facades\Cache;

class RedisOtpStore implements OtpStoreInterface
{
    private function otpKey(string $phone): string
    {
        return 'otp:'.$phone;
    }

    public function put(string $phone, string $codeHash, int $ttlSeconds): void
    {
        $data = [
            'code' => $codeHash,
            'exp' => time() + $ttlSeconds,
            'attempts' => 0,
        ];
        Cache::put($this->otpKey($phone), $data, $ttlSeconds);
    }

    public function get(string $phone): ?array
    {
        $data = Cache::get($this->otpKey($phone));
        if (!$data) {
            return null;
        }
        $lockExp = Cache::get('otp:lock:'.$phone);
        $data['locked_until'] = $lockExp ? $lockExp : null;
        return $data;
    }

    public function incrAttempts(string $phone): int
    {
        $data = Cache::get($this->otpKey($phone));
        if (!$data) {
            return 0;
        }
        $data['attempts'] = ($data['attempts'] ?? 0) + 1;
        $ttl = max(0, $data['exp'] - time());
        Cache::put($this->otpKey($phone), $data, $ttl);
        return $data['attempts'];
    }

    public function lock(string $phone, int $seconds): void
    {
        Cache::put('otp:lock:'.$phone, time() + $seconds, $seconds);
    }

    public function canSend(string $phone, string $ip): bool
    {
        $p = Cache::get('otp:sent:phone:'.$phone, 0);
        $i = Cache::get('otp:sent:ip:'.$ip, 0);
        return $p < 3 && $i < 5;
    }

    public function markSent(string $phone, string $ip): void
    {
        Cache::increment('otp:sent:phone:'.$phone);
        Cache::put('otp:sent:phone:'.$phone, Cache::get('otp:sent:phone:'.$phone), 300);
        Cache::increment('otp:sent:ip:'.$ip);
        Cache::put('otp:sent:ip:'.$ip, Cache::get('otp:sent:ip:'.$ip), 60);
    }

    public function delete(string $phone): void
    {
        Cache::forget($this->otpKey($phone));
        Cache::forget('otp:lock:'.$phone);
    }
}
