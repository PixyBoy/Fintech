<?php

namespace App\Modules\Orders\Domain\Entities;

use App\Modules\Orders\Domain\Enums\OrderStatus;

class Order
{
    /**
     * @param array<string,mixed>|null $meta
     * @param array<string,mixed> $quoteBreakdown
     */
    public function __construct(
        public int $userId,
        public string $serviceKey,
        public string $amountUsd,
        public string $feeUsd,
        public string $subtotalUsd,
        public string $rateUsed,
        public string $totalIrr,
        public OrderStatus $status,
        public ?array $meta,
        public array $quoteBreakdown,
        public ?int $id = null,
    ) {}
}
