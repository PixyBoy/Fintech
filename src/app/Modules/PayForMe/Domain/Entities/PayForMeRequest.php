<?php

namespace App\Modules\PayForMe\Domain\Entities;

use App\Modules\PayForMe\Domain\Enums\PayForMeStatus;

class PayForMeRequest
{
    public function __construct(
        public int $id,
        public int $user_id,
        public string $request_code,
        public string $target_url,
        public float $amount_usd,
        public ?string $notes,
        public array $attachments,
        public array $quote_snapshot,
        public ?int $order_id,
        public PayForMeStatus $status,
    ) {
    }
}
