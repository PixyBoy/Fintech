<?php

namespace App\Modules\PayForMe\Application\DTOs;

use App\Modules\PayForMe\Domain\Enums\PayForMeStatus;

class RequestView
{
    public function __construct(
        public int $id,
        public string $request_code,
        public float $amount_usd,
        public float $total_irr,
        public PayForMeStatus $status,
    ) {
    }
}
