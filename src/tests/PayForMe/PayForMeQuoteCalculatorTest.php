<?php

namespace Tests\PayForMe;

use App\Modules\PayForMe\Application\Services\Quote\QuoteCalculator;
use App\Modules\PayForMe\PayForMeServiceProvider;
use Tests\TestCase;

class PayForMeQuoteCalculatorTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        app()->register(PayForMeServiceProvider::class);
    }

    public function test_percent_rule_applied()
    {
        $calc = new QuoteCalculator();
        $dto = $calc->calculate('payforme', 10);
        $this->assertEquals(0.5, $dto->fee_usd);
    }

    public function test_fixed_rule_applied()
    {
        $calc = new QuoteCalculator();
        $dto = $calc->calculate('payforme', 250);
        $this->assertEquals(4, $dto->fee_usd);
    }
}
