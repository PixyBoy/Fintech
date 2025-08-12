<?php

namespace App\Modules\Rates\Application\DTOs;

class QuoteInput
{
    public function __construct(
        public string $serviceKey,
        public string $amountUsd,
    ) {}
}
