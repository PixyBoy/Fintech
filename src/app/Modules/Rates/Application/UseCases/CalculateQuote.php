<?php

namespace App\Modules\Rates\Application\UseCases;

use App\Modules\Rates\Application\DTOs\QuoteInput;
use App\Modules\Rates\Application\DTOs\QuoteResult;
use App\Modules\Rates\Domain\Repositories\RateRepositoryInterface;
use App\Modules\Rates\Domain\Services\FeeEngineInterface;

class CalculateQuote
{
    public function __construct(
        private RateRepositoryInterface $rates,
        private FeeEngineInterface $fees,
    ) {}

    public function __invoke(QuoteInput $input): QuoteResult
    {
        $rate = $this->rates->latest();
        if (! $rate) {
            throw new \RuntimeException('No rates available');
        }

        $field = config("rates.service_overrides.{$input->serviceKey}")
            ?? config('rates.default_rate_field');

        $rateUsed = $field === 'usd_buy' ? $rate->usdBuy : $rate->usdSell;

        $feeUsd = $this->fees->compute($input->serviceKey, $input->amountUsd);
        $subtotalUsd = bcadd($input->amountUsd, $feeUsd, 4);
        $totalIrr = (string) round((float) bcmul($subtotalUsd, $rateUsed, 4));

        return new QuoteResult(
            $input->amountUsd,
            $feeUsd,
            $subtotalUsd,
            $rateUsed,
            $totalIrr,
        );
    }
}
