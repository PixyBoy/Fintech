<?php

namespace App\Modules\SharedKernel\Infrastructure\Support;

final class Idempotency
{
    public static function idempotencyKey(array $parts): string
    {
        $normalized = self::normalize($parts);
        $json = json_encode($normalized, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        return sha1($json);
    }
    private static function normalize(array $data): array
    {
        if (self::isAssoc($data)) {
            ksort($data);
        }

        foreach ($data as $k => $v) {
            if (is_array($v)) {
                $data[$k] = self::normalize($v);
            }
        }
        return $data;
    }

    private static function isAssoc(array $arr): bool
    {
        return array_keys($arr) !== range(0, count($arr) - 1);
    }
}
