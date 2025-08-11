<?php

namespace App\Modules\Rates;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Modules\Rates\Domain\Repositories\RateRepositoryInterface;
use App\Modules\Rates\Domain\Repositories\FeeRuleRepositoryInterface;
use App\Modules\Rates\Domain\Services\FeeEngineInterface;
use App\Modules\Rates\Infrastructure\Persistence\Eloquent\Repositories\RateRepository;
use App\Modules\Rates\Infrastructure\Persistence\Eloquent\Repositories\FeeRuleRepository;
use App\Modules\Rates\Infrastructure\Services\FeeEngine;
use App\Modules\Rates\Events\RatesUpdated;
use App\Modules\Rates\Events\FeeRulesChanged;
use App\Modules\Rates\Listeners\ClearRatesCache;
use App\Modules\Rates\Listeners\ClearFeeRulesCache;

class RatesServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(RateRepositoryInterface::class, RateRepository::class);
        $this->app->bind(FeeRuleRepositoryInterface::class, FeeRuleRepository::class);
        $this->app->bind(FeeEngineInterface::class, FeeEngine::class);
    }

    public function boot(): void
    {
        Event::listen(RatesUpdated::class, [ClearRatesCache::class, 'handle']);
        Event::listen(FeeRulesChanged::class, [ClearFeeRulesCache::class, 'handle']);
    }
}
