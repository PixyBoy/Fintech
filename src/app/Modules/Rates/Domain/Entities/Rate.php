<?php

namespace App\Modules\Rates\Domain\Entities;

use Carbon\CarbonImmutable;

class Rate
{
    public function __construct(
        public string $baseCurrency,
        public string $usdBuy,
        public string $usdSell,
        public ?CarbonImmutable $updatedAt = null,
    ) {}
}
