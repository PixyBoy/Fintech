<?php

namespace App\Modules\Rates\Domain\Entities;

class Rate
{
    public function __construct(
        public string $baseCurrency,
        public string $usdBuy,
        public string $usdSell,
        public ?\DateTimeInterface $updatedAt = null,
    ) {}
}
