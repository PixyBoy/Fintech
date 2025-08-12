<?php

namespace App\Modules\Rates\Application\DTOs;

class QuoteResult
{
    public function __construct(
        public string $amountUsd,
        public string $feeUsd,
        public string $subtotalUsd,
        public string $rateUsed,
        public string $totalIrr,
    ) {}
}
