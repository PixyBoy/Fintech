<?php

namespace App\Modules\PayForMe\Application\DTOs;

use Illuminate\Http\UploadedFile;

class CreateRequestInput
{
    /** @param UploadedFile[] $attachments */
    public function __construct(
        public int $user_id,
        public string $target_url,
        public float $amount_usd,
        public ?string $notes,
        public array $attachments = [],
    ) {
    }
}
