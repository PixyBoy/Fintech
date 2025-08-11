<?php

namespace App\Modules\Rates\Application\Livewire\Admin;

use Livewire\Component;
use App\Modules\Rates\Application\UseCases\CalculateQuote;
use App\Modules\Rates\Application\DTOs\QuoteInput;

class QuoteTester extends Component
{
    public string $serviceKey = 'payforme';
    public string $amountUsd = '0';
    public ?array $result = null;

    public function submit(CalculateQuote $calc): void
    {
        $this->validate([
            'serviceKey' => 'required|string',
            'amountUsd' => 'required|numeric|gt:0',
        ]);

        $dto = new QuoteInput($this->serviceKey, $this->amountUsd);
        $res = $calc($dto);
        $this->result = [
            'amount_usd' => $res->amountUsd,
            'fee_usd' => $res->feeUsd,
            'subtotal_usd' => $res->subtotalUsd,
            'rate_used' => $res->rateUsed,
            'total_irr' => $res->totalIrr,
        ];
    }

    public function render()
    {
        return view('rates::admin.quote.tester');
    }
}
