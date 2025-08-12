<?php

namespace App\Modules\Rates\Infrastructure\Persistence\Eloquent\Repositories;

use App\Modules\Rates\Domain\Entities\Rate;
use App\Modules\Rates\Domain\Repositories\RateRepositoryInterface;
use App\Modules\Rates\Infrastructure\Persistence\Eloquent\Models\RateModel;
use App\Modules\Rates\Events\RatesUpdated;
use Illuminate\Support\Facades\Cache;

class RateRepository implements RateRepositoryInterface
{
    public function latest(): ?Rate
    {
        $ttl = config('rates.cache_ttl');
        $key = 'rates:current';

        if ($ttl > 0) {
            return Cache::remember($key, $ttl, function () {
                $model = RateModel::query()->latest()->first();
                return $model ? $this->map($model) : null;
            });
        }

        $model = RateModel::query()->latest()->first();
        return $model ? $this->map($model) : null;
    }

    public function upsert(Rate $rate): Rate
    {
        $model = RateModel::query()->create([
            'base_currency' => $rate->baseCurrency,
            'usd_buy' => $rate->usdBuy,
            'usd_sell' => $rate->usdSell,
        ]);

        Cache::forget('rates:current');
        event(new RatesUpdated());

        return $this->map($model);
    }

    protected function map(RateModel $model): Rate
    {
        return new Rate(
            $model->base_currency,
            $model->usd_buy,
            $model->usd_sell,
            $model->updated_at,
        );
    }
}
