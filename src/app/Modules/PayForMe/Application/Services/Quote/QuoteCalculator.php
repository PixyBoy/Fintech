<?php

namespace App\Modules\PayForMe\Application\Services\Quote;

use App\Modules\PayForMe\Application\DTOs\QuoteDto;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\App;

class QuoteCalculator
{
    public function calculate(string $serviceKey, float $amountUsd): QuoteDto
    {
        if (class_exists('App\\Modules\\Rates\\Application\\UseCases\\CalculateQuote')) {
            $calc = App::make('App\\Modules\\Rates\\Application\\UseCases\\CalculateQuote');
            $result = $calc->execute($serviceKey, $amountUsd);
            return new QuoteDto(
                $result->amountUsd,
                $result->feeUsd,
                $result->subtotalUsd,
                $result->rateUsed,
                $result->totalIrr
            );
        }

        $config = Config::get('payforme.quote.stub');
        $rate = $config['usd_sell'];
        $feeRules = $config['fee_rules'];
        $fee = 0;
        foreach ($feeRules as $rule) {
            if ($amountUsd >= $rule['from'] && $amountUsd < $rule['to']) {
                if ($rule['type'] === 'percent') {
                    $fee = $amountUsd * ($rule['value'] / 100);
                } else {
                    $fee = $rule['value'];
                }
                break;
            }
        }
        $subtotal = $amountUsd + $fee;
        $totalIrr = round($subtotal * $rate);
        return new QuoteDto($amountUsd, $fee, $subtotal, $rate, $totalIrr);
    }
}
