<?php

namespace App\Modules\Rates\Domain\Repositories;

use App\Modules\Rates\Domain\Entities\FeeRule;

interface FeeRuleRepositoryInterface
{
    /** @return FeeRule[] */
    public function forService(string $serviceKey): array;

    public function upsert(FeeRule $rule): FeeRule;
}
