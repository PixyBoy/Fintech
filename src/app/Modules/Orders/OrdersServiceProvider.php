<?php

namespace App\Modules\Orders;

use Illuminate\Support\ServiceProvider;
use App\Modules\Orders\Domain\Repositories\OrderRepositoryInterface;
use App\Modules\Orders\Infrastructure\Persistence\Eloquent\Repositories\OrderRepository;

class OrdersServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
    }

    public function boot(): void
    {
    }
}
