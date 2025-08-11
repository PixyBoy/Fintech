<?php

namespace App\Modules\Orders\Domain\Entities;

class OrderItem
{
    public function __construct(
        public int $orderId,
        public ?string $sku,
        public string $title,
        public string $unitPriceUsd,
        public int $qty,
        public string $lineTotalUsd,
        public ?array $meta = null,
        public ?int $id = null,
    ) {}
}
