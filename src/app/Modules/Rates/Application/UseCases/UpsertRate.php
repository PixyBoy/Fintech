<?php

namespace App\Modules\Rates\Application\UseCases;

use App\Modules\Rates\Domain\Entities\Rate;
use App\Modules\Rates\Domain\Repositories\RateRepositoryInterface;

class UpsertRate
{
    public function __construct(private RateRepositoryInterface $repo)
    {
    }

    public function __invoke(string $usdBuy, string $usdSell): Rate
    {
        $rate = new Rate('IRR', $usdBuy, $usdSell);
        return $this->repo->upsert($rate);
    }
}
