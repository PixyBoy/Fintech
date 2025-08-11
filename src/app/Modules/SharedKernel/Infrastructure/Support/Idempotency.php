<?php

namespace App\Modules\SharedKernel\Infrastructure\Support;

trait Idempotency
{
    public static function idempotencyKey(array $parts): string
    {
        return sha1(implode(':', $parts));
    }
}
