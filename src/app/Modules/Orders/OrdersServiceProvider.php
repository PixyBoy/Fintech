<?php

namespace App\Modules\Orders;

use Illuminate\Support\ServiceProvider;

class OrdersServiceProvider extends ServiceProvider
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
