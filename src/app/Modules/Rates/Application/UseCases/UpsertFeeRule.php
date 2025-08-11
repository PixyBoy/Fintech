<?php

namespace App\Modules\Rates\Application\UseCases;

use App\Modules\Rates\Domain\Entities\FeeRule;
use App\Modules\Rates\Domain\Enums\FeeType;
use App\Modules\Rates\Domain\Repositories\FeeRuleRepositoryInterface;

class UpsertFeeRule
{
    public function __construct(private FeeRuleRepositoryInterface $rules) {}

    public function __invoke(
        string $serviceKey,
        string $fromAmount,
        string $toAmount,
        FeeType $feeType,
        string $value,
        bool $isActive = true,
    ): FeeRule {
        $rule = new FeeRule($serviceKey, $fromAmount, $toAmount, $feeType, $value, $isActive);
        return $this->rules->upsert($rule);
    }
}
