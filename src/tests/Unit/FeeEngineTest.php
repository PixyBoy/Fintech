<?php

namespace Tests\Unit;

use App\Modules\Rates\Domain\Entities\FeeRule;
use App\Modules\Rates\Domain\Enums\FeeType;
use App\Modules\Rates\Domain\Repositories\FeeRuleRepositoryInterface;
use App\Modules\Rates\Infrastructure\Services\FeeEngine;
use PHPUnit\Framework\TestCase;

class FeeEngineTest extends TestCase
{
    public function test_percent_fee_rule(): void
    {
        $repo = new class implements FeeRuleRepositoryInterface {
            public function forService(string $serviceKey): array { return [ new FeeRule($serviceKey,'0','200',FeeType::Percent,'5') ]; }
            public function upsert(FeeRule $rule): FeeRule { return $rule; }
        };
        $engine = new FeeEngine($repo);
        $this->assertSame('5.0000', $engine->compute('svc', '100'));
    }

    public function test_fixed_fee_rule(): void
    {
        $repo = new class implements FeeRuleRepositoryInterface {
            public function forService(string $serviceKey): array { return [ new FeeRule($serviceKey,'0','200',FeeType::Fixed,'3') ]; }
            public function upsert(FeeRule $rule): FeeRule { return $rule; }
        };
        $engine = new FeeEngine($repo);
        $this->assertSame('3', $engine->compute('svc', '100'));
    }

    public function test_no_matching_rule(): void
    {
        $repo = new class implements FeeRuleRepositoryInterface {
            public function forService(string $serviceKey): array { return []; }
            public function upsert(FeeRule $rule): FeeRule { return $rule; }
        };
        $engine = new FeeEngine($repo);
        $this->assertSame('0', $engine->compute('svc', '100'));
    }
}
