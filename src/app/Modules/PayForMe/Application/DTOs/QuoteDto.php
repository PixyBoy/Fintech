<?php

namespace App\Modules\PayForMe\Application\DTOs;

class QuoteDto
{
    public function __construct(
        public float $amount_usd,
        public float $fee_usd,
        public float $subtotal_usd,
        public float $rate_used,
        public float $total_irr,
    ) {
    }
}
