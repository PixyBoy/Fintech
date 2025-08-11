<?php

namespace App\Modules\Rates\Domain\Entities;

use App\Modules\Rates\Domain\Enums\FeeType;

class FeeRule
{
    public function __construct(
        public string $serviceKey,
        public string $fromAmount,
        public string $toAmount,
        public FeeType $feeType,
        public string $value,
        public bool $isActive = true,
    ) {}

    public function matches(string $amountUsd): bool
    {
        return $this->isActive
            && bccomp($amountUsd, $this->fromAmount, 2) >= 0
            && bccomp($amountUsd, $this->toAmount, 2) <= 0;
    }
}
