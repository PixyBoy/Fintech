<?php

namespace App\Modules\Orders\Application\DTOs;

use App\Modules\Orders\Domain\Enums\OrderStatus;

class OrderView
{
    public function __construct(
        public int $id,
        public string $serviceKey,
        public string $amountUsd,
        public string $totalIrr,
        public OrderStatus $status,
    ) {}
}
