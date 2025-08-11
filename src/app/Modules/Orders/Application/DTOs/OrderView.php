<?php

namespace App\Modules\Orders\Application\DTOs;

use App\Modules\Orders\Domain\Enums\OrderStatus;

class OrderView
{
    /**
     * @param array<string,mixed> $quoteBreakdown
     */
    public function __construct(
        public int $id,
        public string $serviceKey,
        public OrderStatus $status,
        public string $totalIrr,
        public array $quoteBreakdown,
    ) {}
}
