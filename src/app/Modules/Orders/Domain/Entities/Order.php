<?php

namespace App\Modules\Orders\Domain\Entities;

use App\Modules\Orders\Domain\Enums\OrderStatus;

class Order
{
    public function __construct(
        public int $userId,
        public string $serviceKey,
        public string $amountUsd,
        public string $feeUsd,
        public string $subtotalUsd,
        public string $rateUsed,
        public string $totalIrr,
        public OrderStatus $status,
        public array $meta = [],
        public array $quoteBreakdown = [],
    ) {}
}
