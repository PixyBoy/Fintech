<?php

namespace App\Modules\PayForMe\Application\Livewire\Public;

use App\Modules\PayForMe\Application\Services\Quote\QuoteCalculator;
use Livewire\Component;

class QuoteWidget extends Component
{
    public string $serviceKey = 'payforme';
    public $amountUsd = 0;
    public ?array $quote = null;

    public function updatedAmountUsd()
    {
        $calc = app(QuoteCalculator::class);
        $dto = $calc->calculate($this->serviceKey, (float) $this->amountUsd);
        $this->quote = [
            'amount_usd' => $dto->amount_usd,
            'fee_usd' => $dto->fee_usd,
            'subtotal_usd' => $dto->subtotal_usd,
            'rate_used' => $dto->rate_used,
            'total_irr' => $dto->total_irr,
        ];
    }

    public function render()
    {
        return view('pay-for-me::public.quote-widget');
    }
}
