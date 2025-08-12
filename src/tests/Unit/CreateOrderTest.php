<?php

namespace Tests\Unit;

use App\Modules\Orders\Application\DTOs\CreateOrderInput;
use App\Modules\Orders\Application\UseCases\CreateOrder;
use App\Modules\Orders\Domain\Entities\Order;
use App\Modules\Orders\Domain\Enums\OrderStatus;
use App\Modules\Orders\Domain\Repositories\OrderRepositoryInterface;
use App\Modules\Rates\Application\UseCases\CalculateQuote;
use Tests\TestCase;

class CreateOrderTest extends TestCase
{
    public function test_creates_order_with_quote_snapshot(): void
    {
        $orderRepo = new class implements OrderRepositoryInterface {
            public ?Order $created = null;
            public function create(Order $order): Order { $order->id = 1; $this->created = $order; return $order; }
            public function find(int $id): ?Order { return null; }
        };
        $quoteUseCase = new CalculateQuote(
            new class implements \App\Modules\Rates\Domain\Repositories\RateRepositoryInterface {
                public function latest(): ?\App\Modules\Rates\Domain\Entities\Rate { return new \App\Modules\Rates\Domain\Entities\Rate('IRR','50000','60000'); }
                public function upsert(\App\Modules\Rates\Domain\Entities\Rate $rate): \App\Modules\Rates\Domain\Entities\Rate { return $rate; }
            },
            new class implements \App\Modules\Rates\Domain\Services\FeeEngineInterface {
                public function compute(string $serviceKey, string $amountUsd): string { return '5'; }
            }
        );
        $useCase = new CreateOrder($orderRepo, $quoteUseCase);
        $input = new CreateOrderInput(1,'payforme','100');
        $view = $useCase($input);
        $this->assertSame(1, $view->id);
        $this->assertSame('payforme', $view->serviceKey);
        $this->assertSame(OrderStatus::Pending, $view->status);
        $this->assertSame((string) round(105*60000), $view->totalIrr);
        $this->assertEquals('105.0000', $orderRepo->created->subtotalUsd);
    }
}
