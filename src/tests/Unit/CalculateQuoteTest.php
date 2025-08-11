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
        $rateRepo = new class implements RateRepositoryInterface {
            public function latest(): ?Rate { return new Rate('IRR','50000','60000'); }
            public function upsert(Rate $rate): Rate { return $rate; }
        };
        $feeEngine = new class implements FeeEngineInterface {
            public function compute(string $serviceKey, string $amountUsd): string { return '5'; }
        };
        $useCase = new CalculateQuote($rateRepo, $feeEngine);
        $result = $useCase(new QuoteInput('payforme','100'));
        $this->assertSame('100', $result->amountUsd);
        $this->assertSame('5', $result->feeUsd);
        $this->assertSame('105.0000', $result->subtotalUsd);
        $this->assertSame('60000', $result->rateUsed);
        $this->assertSame((string) round(105*60000), $result->totalIrr);
    }
}
