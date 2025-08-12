<?php

namespace App\Modules\PayForMe;

use Illuminate\Support\ServiceProvider;
use App\Modules\PayForMe\Domain\Repositories\PayForMeRepositoryInterface;
use App\Modules\PayForMe\Infrastructure\Persistence\Eloquent\Repositories\PayForMeRepository;

class PayForMeServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/config.php', 'payforme');
        $this->app->bind(PayForMeRepositoryInterface::class, PayForMeRepository::class);
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadRoutesFrom(__DIR__.'/routes/admin.php');
        $this->loadMigrationsFrom(__DIR__.'/Database/Migrations');
        $this->loadViewsFrom(__DIR__.'/Views', 'payforme');
    }
}
