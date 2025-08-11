<?php

namespace App\Modules\Orders\Infrastructure\Persistence\Eloquent\Repositories;

use App\Modules\Orders\Domain\Entities\Order;
use App\Modules\Orders\Domain\Enums\OrderStatus;
use App\Modules\Orders\Domain\Repositories\OrderRepositoryInterface;
use App\Modules\Orders\Infrastructure\Persistence\Eloquent\Models\OrderModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class OrderRepository implements OrderRepositoryInterface
{
    public function create(Order $order): Order
    {
        $model = OrderModel::query()->create([
            'user_id' => $order->userId,
            'service_key' => $order->serviceKey,
            'currency' => 'USD',
            'amount_usd' => $order->amountUsd,
            'fee_usd' => $order->feeUsd,
            'subtotal_usd' => $order->subtotalUsd,
            'rate_used' => $order->rateUsed,
            'total_irr' => $order->totalIrr,
            'meta' => $order->meta,
            'quote_breakdown' => $order->quoteBreakdown,
            'status' => $order->status->value,
        ]);

        return $this->map($model);
    }

    public function find(int $id): ?Order
    {
        $model = OrderModel::query()->find($id);
        return $model ? $this->map($model) : null;
    }

    public function paginate(array $filters = []): LengthAwarePaginator
    {
        $query = OrderModel::query();
        if (isset($filters['service_key'])) {
            $query->where('service_key', $filters['service_key']);
        }
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        return $query->paginate();
    }

    protected function map(OrderModel $m): Order
    {
        return new Order(
            $m->user_id,
            $m->service_key,
            $m->amount_usd,
            $m->fee_usd,
            $m->subtotal_usd,
            $m->rate_used,
            $m->total_irr,
            OrderStatus::from($m->status),
            $m->quote_breakdown ?? [],
            $m->meta ?? [],
            $m->id,
        );
    }
}
