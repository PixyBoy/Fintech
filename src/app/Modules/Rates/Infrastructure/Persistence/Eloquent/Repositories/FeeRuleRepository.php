<?php

namespace App\Modules\Rates\Infrastructure\Persistence\Eloquent\Repositories;

use App\Modules\Rates\Domain\Entities\FeeRule;
use App\Modules\Rates\Domain\Enums\FeeType;
use App\Modules\Rates\Domain\Repositories\FeeRuleRepositoryInterface;
use App\Modules\Rates\Infrastructure\Persistence\Eloquent\Models\FeeRuleModel;
use App\Modules\Rates\Events\FeeRulesChanged;
use Illuminate\Support\Facades\Cache;

class FeeRuleRepository implements FeeRuleRepositoryInterface
{
    public function forService(string $serviceKey): array
    {
        $key = "fees:$serviceKey";
        $ttl = config('rates.cache_ttl');

        $callback = function () use ($serviceKey) {
            return FeeRuleModel::query()
                ->where('service_key', $serviceKey)
                ->orderBy('from_amount')
                ->get()
                ->map(fn($m) => $this->map($m))
                ->all();
        };

        if ($ttl > 0) {
            return Cache::remember($key, $ttl, $callback);
        }

        return $callback();
    }

    public function upsert(FeeRule $rule): FeeRule
    {
        $model = FeeRuleModel::query()->create([
            'service_key' => $rule->serviceKey,
            'from_amount' => $rule->fromAmount,
            'to_amount' => $rule->toAmount,
            'fee_type' => $rule->feeType->value,
            'value' => $rule->value,
            'is_active' => $rule->isActive,
        ]);

        Cache::forget("fees:{$rule->serviceKey}");
        event(new FeeRulesChanged($rule->serviceKey));

        return $this->map($model);
    }

    protected function map(FeeRuleModel $m): FeeRule
    {
        return new FeeRule(
            $m->service_key,
            $m->from_amount,
            $m->to_amount,
            FeeType::from($m->fee_type),
            $m->value,
            (bool)$m->is_active,
        );
    }
}
