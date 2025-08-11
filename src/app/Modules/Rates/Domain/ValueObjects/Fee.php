<?php

namespace App\Modules\Rates\Domain\ValueObjects;

use App\Modules\Rates\Domain\Enums\FeeType;

class Fee
{
    public function __construct(
        public FeeType $type,
        public string $value,
    ) {}
}
