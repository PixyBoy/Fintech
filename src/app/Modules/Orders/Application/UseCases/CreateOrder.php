<?php

namespace App\Modules\Orders\Application\UseCases;

use App\Modules\Orders\Application\DTOs\CreateOrderInput;
use App\Modules\Orders\Application\DTOs\OrderView;
use App\Modules\Orders\Domain\Entities\Order;
use App\Modules\Orders\Domain\Enums\OrderStatus;
use App\Modules\Orders\Domain\Repositories\OrderRepositoryInterface;
use App\Modules\Rates\Application\DTOs\QuoteInput;
use App\Modules\Rates\Application\UseCases\CalculateQuote;

class CreateOrder
{
    public function __construct(
        private OrderRepositoryInterface $orders,
        private CalculateQuote $calculateQuote,
    ) {}

    public function __invoke(CreateOrderInput $input): OrderView
    {
        $quote = ($this->calculateQuote)(new QuoteInput($input->serviceKey, $input->amountUsd));

        $order = new Order(
            $input->userId,
            $input->serviceKey,
            $quote->amountUsd,
            $quote->feeUsd,
            $quote->subtotalUsd,
            $quote->rateUsed,
            $quote->totalIrr,
            OrderStatus::Pending,
            $input->meta,
            [
                'amount_usd' => $quote->amountUsd,
                'fee_usd' => $quote->feeUsd,
                'subtotal_usd' => $quote->subtotalUsd,
                'rate_used' => $quote->rateUsed,
                'total_irr' => $quote->totalIrr,
            ],
        );

        $created = $this->orders->create($order);

        return new OrderView(
            $created->id ?? 0,
            $created->serviceKey,
            $created->status,
            $created->totalIrr,
            $created->quoteBreakdown,
        );
    }
}
