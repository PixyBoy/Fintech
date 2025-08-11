<?php

namespace App\Modules\Auth\Domain\Contracts;

interface OtpStoreInterface
{
    public function put(string $phone, string $codeHash, int $ttlSeconds): void;

    /**
     * @return array{code?:string,exp?:int,attempts?:int,locked_until?:?int}|null
     */
    public function get(string $phone): ?array;

    public function incrAttempts(string $phone): int;

    public function lock(string $phone, int $seconds): void;

    public function canSend(string $phone, string $ip): bool;

    public function markSent(string $phone, string $ip): void;

    public function delete(string $phone): void;
}
