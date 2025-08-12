<?php

namespace Tests\Unit;

use App\Modules\Rates\Application\DTOs\QuoteInput;
use App\Modules\Rates\Application\UseCases\CalculateQuote;
use App\Modules\Rates\Domain\Entities\Rate;
use App\Modules\Rates\Domain\Repositories\RateRepositoryInterface;
use App\Modules\Rates\Domain\Services\FeeEngineInterface;
use Tests\TestCase;

class CalculateQuoteTest extends TestCase
{
    public function test_calculates_quote(): void
    {
        $rates = new class implements RateRepositoryInterface {
            public function latest(): ?Rate { return new Rate('IRR', '600000', '610000'); }
            public function upsert(Rate $rate): Rate { return $rate; }
        };

        $fees = new class implements FeeEngineInterface {
            public function compute(string $serviceKey, string $amountUsd): string { return '5'; }
        };

        $useCase = new CalculateQuote($rates, $fees);
        $result = $useCase(new QuoteInput('payforme', '10'));

        $this->assertSame('10', $result->amountUsd);
        $this->assertSame('5', $result->feeUsd);
        $this->assertSame('15.0000', $result->subtotalUsd);
        $this->assertSame('610000', $result->rateUsed);
        $this->assertEquals(9150000, $result->totalIrr);
    }
}
