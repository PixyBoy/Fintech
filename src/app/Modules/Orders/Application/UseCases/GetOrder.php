<?php

namespace App\Modules\Orders\Application\UseCases;

use App\Modules\Orders\Application\DTOs\OrderView;
use App\Modules\Orders\Domain\Repositories\OrderRepositoryInterface;

class GetOrder
{
    public function __construct(private OrderRepositoryInterface $repo)
    {
    }

    public function __invoke(int $id): ?OrderView
    {
        $order = $this->repo->find($id);
        if (! $order) {
            return null;
        }

        return new OrderView(
            $order->id ?? 0,
            $order->serviceKey,
            $order->amountUsd,
            $order->totalIrr,
            $order->status,
        );
    }
}
