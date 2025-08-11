<?php

namespace App\Modules\Rates\Infrastructure\Services;

use App\Modules\Rates\Domain\Enums\FeeType;
use App\Modules\Rates\Domain\Repositories\FeeRuleRepositoryInterface;
use App\Modules\Rates\Domain\Services\FeeEngineInterface;

class FeeEngine implements FeeEngineInterface
{
    public function __construct(private FeeRuleRepositoryInterface $rules) {}

    public function compute(string $serviceKey, string $amountUsd): string
    {
        foreach ($this->rules->forService($serviceKey) as $rule) {
            if ($rule->matches($amountUsd)) {
                return match ($rule->feeType) {
                    FeeType::Fixed => $rule->value,
                    FeeType::Percent => bcdiv(bcmul($amountUsd, $rule->value, 4), '100', 4),
                };
            }
        }
        return '0';
    }
}
