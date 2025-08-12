<?php

namespace App\Modules\Orders\Domain\Repositories;

use App\Modules\Orders\Domain\Entities\Order;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface OrderRepositoryInterface
{
    public function create(Order $order): Order;

    public function find(int $id): ?Order;

    public function paginate(array $filters = []): LengthAwarePaginator;
}
