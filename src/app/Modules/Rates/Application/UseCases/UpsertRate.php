<?php

namespace App\Modules\Rates\Application\UseCases;

use App\Modules\Rates\Domain\Entities\Rate;
use App\Modules\Rates\Domain\Repositories\RateRepositoryInterface;

class UpsertRate
{
    public function __construct(private RateRepositoryInterface $rates) {}

    public function __invoke(string $usdBuy, string $usdSell, string $baseCurrency = 'IRR'): Rate
    {
        $rate = new Rate($baseCurrency, $usdBuy, $usdSell);
        return $this->rates->upsert($rate);
    }
}
