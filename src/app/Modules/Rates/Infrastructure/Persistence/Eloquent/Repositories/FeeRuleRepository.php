<?php

namespace App\Modules\Rates\Infrastructure\Persistence\Eloquent\Repositories;

use App\Modules\Rates\Domain\Entities\FeeRule;
use App\Modules\Rates\Domain\Enums\FeeType;
use App\Modules\Rates\Domain\Repositories\FeeRuleRepositoryInterface;
use App\Modules\Rates\Infrastructure\Persistence\Eloquent\Models\FeeRuleModel;
use Illuminate\Support\Facades\Cache;

class FeeRuleRepository implements FeeRuleRepositoryInterface
{
    public function forService(string $serviceKey): array
    {
        $ttl = config('rates.cache_ttl', 0);
        $key = "fees:{$serviceKey}";
        if ($ttl > 0 && ($cached = Cache::get($key))) {
            return $cached;
        }

        $models = FeeRuleModel::where('service_key', $serviceKey)
            ->orderBy('from_amount')
            ->get();

        $rules = $models->map(fn($m) => new FeeRule(
            $m->service_key,
            (string) $m->from_amount,
            (string) $m->to_amount,
            FeeType::from($m->fee_type),
            (string) $m->value,
            (bool) $m->is_active,
        ))->all();

        if ($ttl > 0) {
            Cache::put($key, $rules, $ttl);
        }
        return $rules;
    }

    public function upsert(FeeRule $rule): FeeRule
    {
        $model = FeeRuleModel::updateOrCreate(
            [
                'service_key' => $rule->serviceKey,
                'from_amount' => $rule->fromAmount,
                'to_amount' => $rule->toAmount,
            ],
            [
                'fee_type' => $rule->feeType->value,
                'value' => $rule->value,
                'is_active' => $rule->isActive,
            ]
        );

        Cache::forget("fees:{$rule->serviceKey}");

        return new FeeRule(
            $model->service_key,
            (string) $model->from_amount,
            (string) $model->to_amount,
            FeeType::from($model->fee_type),
            (string) $model->value,
            (bool) $model->is_active,
        );
    }
}
