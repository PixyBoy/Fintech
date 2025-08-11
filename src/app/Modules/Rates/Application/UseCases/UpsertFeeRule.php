<?php

namespace App\Modules\Rates\Application\UseCases;

use App\Modules\Rates\Domain\Entities\FeeRule;
use App\Modules\Rates\Domain\Enums\FeeType;
use App\Modules\Rates\Domain\Repositories\FeeRuleRepositoryInterface;

class UpsertFeeRule
{
    public function __construct(private FeeRuleRepositoryInterface $repo)
    {
    }

    public function __invoke(
        string $serviceKey,
        string $fromAmount,
        string $toAmount,
        string $feeType,
        string $value,
        bool $isActive = true,
    ): FeeRule {
        $rule = new FeeRule(
            $serviceKey,
            $fromAmount,
            $toAmount,
            FeeType::from($feeType),
            $value,
            $isActive,
        );

        return $this->repo->upsert($rule);
    }
}
