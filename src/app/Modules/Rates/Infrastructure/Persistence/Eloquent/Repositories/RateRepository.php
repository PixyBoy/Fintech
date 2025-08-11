<?php

namespace App\Modules\Rates\Infrastructure\Persistence\Eloquent\Repositories;

use App\Modules\Rates\Domain\Entities\Rate;
use App\Modules\Rates\Domain\Repositories\RateRepositoryInterface;
use App\Modules\Rates\Infrastructure\Persistence\Eloquent\Models\RateModel;
use Illuminate\Support\Facades\Cache;

class RateRepository implements RateRepositoryInterface
{
    public function latest(): ?Rate
    {
        $ttl = config('rates.cache_ttl', 0);
        $key = 'rates:current';
        if ($ttl > 0 && ($cached = Cache::get($key))) {
            return $cached;
        }

        $model = RateModel::latest('id')->first();
        if (! $model) {
            return null;
        }

        $rate = new Rate($model->base_currency, (string) $model->usd_buy, (string) $model->usd_sell, $model->updated_at?->toImmutable());
        if ($ttl > 0) {
            Cache::put($key, $rate, $ttl);
        }
        return $rate;
    }

    public function upsert(Rate $rate): Rate
    {
        $model = RateModel::create([
            'base_currency' => $rate->baseCurrency,
            'usd_buy' => $rate->usdBuy,
            'usd_sell' => $rate->usdSell,
        ]);

        Cache::forget('rates:current');

        return new Rate($model->base_currency, (string) $model->usd_buy, (string) $model->usd_sell, $model->updated_at?->toImmutable());
    }
}
