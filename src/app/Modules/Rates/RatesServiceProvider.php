<?php

namespace App\Modules\Rates;

use Illuminate\Support\ServiceProvider;

class RatesServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bindings will be added in future sprints
    }

    public function boot(): void
    {
        if (file_exists(__DIR__.'/routes/web.php')) {
            $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        }
    }
}
