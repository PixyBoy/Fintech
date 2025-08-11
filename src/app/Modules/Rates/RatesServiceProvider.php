<?php

namespace App\Modules\Rates;

use App\Modules\Rates\Domain\Repositories\FeeRuleRepositoryInterface;
use App\Modules\Rates\Domain\Repositories\RateRepositoryInterface;
use App\Modules\Rates\Domain\Services\FeeEngineInterface;
use App\Modules\Rates\Infrastructure\Persistence\Eloquent\Repositories\FeeRuleRepository;
use App\Modules\Rates\Infrastructure\Persistence\Eloquent\Repositories\RateRepository;
use App\Modules\Rates\Infrastructure\Services\FeeEngine;
use Illuminate\Support\ServiceProvider;

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
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadViewsFrom(__DIR__.'/Application/Views', 'rates');
    }
}
