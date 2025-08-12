<?php

namespace App\Modules\Orders\Application\UseCases;

use App\Modules\Orders\Application\DTOs\CreateOrderInput;
use App\Modules\Orders\Domain\Entities\Order;
use App\Modules\Orders\Domain\Enums\OrderStatus;
use App\Modules\Orders\Domain\Repositories\OrderRepositoryInterface;
use App\Modules\Rates\Application\UseCases\CalculateQuote;
use App\Modules\Rates\Application\DTOs\QuoteInput;

class CreateOrder
{
    public function __construct(
        private CalculateQuote $quote,
        private OrderRepositoryInterface $repo,
    ) {}

    public function __invoke(CreateOrderInput $input): Order
    {
        $result = ($this->quote)(new QuoteInput($input->serviceKey, $input->amountUsd));

        $order = new Order(
            $input->userId,
            $input->serviceKey,
            $result->amountUsd,
            $result->feeUsd,
            $result->subtotalUsd,
            $result->rateUsed,
            $result->totalIrr,
            OrderStatus::Pending,
            [
                'amount_usd' => $result->amountUsd,
                'fee_usd' => $result->feeUsd,
                'subtotal_usd' => $result->subtotalUsd,
                'rate_used' => $result->rateUsed,
                'total_irr' => $result->totalIrr,
            ],
            $input->meta,
        );

        return $this->repo->create($order);
    }
}
