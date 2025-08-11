<?php

namespace App\Modules\Orders\Application\DTOs;

class CreateOrderInput
{
    /**
     * @param array<string,mixed>|null $meta
     */
    public function __construct(
        public int $userId,
        public string $serviceKey,
        public string $amountUsd,
        public ?array $meta = null,
    ) {}
}
