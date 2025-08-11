<?php

namespace App\Modules\Orders\Domain\Entities;

class OrderItem
{
    public function __construct(
        public string $title,
        public string $unitPriceUsd,
        public int $qty,
        public string $lineTotalUsd,
        public ?string $sku = null,
        public array $meta = [],
    ) {}
}
