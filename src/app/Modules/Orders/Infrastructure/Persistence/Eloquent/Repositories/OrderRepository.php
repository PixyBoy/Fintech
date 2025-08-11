<?php

namespace App\Modules\Orders\Infrastructure\Persistence\Eloquent\Repositories;

use App\Modules\Orders\Domain\Entities\Order;
use App\Modules\Orders\Domain\Enums\OrderStatus;
use App\Modules\Orders\Domain\Repositories\OrderRepositoryInterface;
use App\Modules\Orders\Infrastructure\Persistence\Eloquent\Models\OrderModel;

class OrderRepository implements OrderRepositoryInterface
{
    public function create(Order $order): Order
    {
        $model = OrderModel::create([
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

        return $this->toDomain($model);
    }

    public function find(int $id): ?Order
    {
        $model = OrderModel::find($id);
        return $model ? $this->toDomain($model) : null;
    }

    protected function toDomain(OrderModel $model): Order
    {
        return new Order(
            $model->user_id,
            $model->service_key,
            (string) $model->amount_usd,
            (string) $model->fee_usd,
            (string) $model->subtotal_usd,
            (string) $model->rate_used,
            (string) $model->total_irr,
            OrderStatus::from($model->status),
            $model->meta,
            $model->quote_breakdown,
            $model->id,
        );
    }
}
