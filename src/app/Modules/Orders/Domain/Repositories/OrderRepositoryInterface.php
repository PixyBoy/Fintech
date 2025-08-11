<?php

namespace App\Modules\Orders\Domain\Repositories;

use App\Modules\Orders\Domain\Entities\Order;

interface OrderRepositoryInterface
{
    public function create(Order $order): Order;

    public function find(int $id): ?Order;
}
