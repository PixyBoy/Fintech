<?php

namespace Tests\Unit;

use App\Modules\Rates\Domain\Entities\FeeRule;
use App\Modules\Rates\Domain\Enums\FeeType;
use App\Modules\Rates\Domain\Repositories\FeeRuleRepositoryInterface;
use App\Modules\Rates\Infrastructure\Services\FeeEngine;
use PHPUnit\Framework\TestCase;

class FeeEngineTest extends TestCase
{
    private function repo(array $rules): FeeRuleRepositoryInterface
    {
        return new class($rules) implements FeeRuleRepositoryInterface {
            public function __construct(private array $rules) {}
            public function forService(string $serviceKey): array { return $this->rules[$serviceKey] ?? []; }
            public function upsert(FeeRule $rule): FeeRule { return $rule; }
        };
    }

    public function test_fixed_fee(): void
    {
        $rule = new FeeRule('pay', '0', '100', FeeType::Fixed, '5');
        $engine = new FeeEngine($this->repo(['pay' => [$rule]]));
        $this->assertSame('5', $engine->compute('pay', '50'));
    }

    public function test_percent_fee(): void
    {
        $rule = new FeeRule('gift', '0', '100', FeeType::Percent, '10');
        $engine = new FeeEngine($this->repo(['gift' => [$rule]]));
        $this->assertSame('5.0000', $engine->compute('gift', '50'));
    }

    public function test_inactive_rule_returns_zero(): void
    {
        $rule = new FeeRule('pay', '0', '100', FeeType::Fixed, '5', false);
        $engine = new FeeEngine($this->repo(['pay' => [$rule]]));
        $this->assertSame('0', $engine->compute('pay', '50'));
    }
}
