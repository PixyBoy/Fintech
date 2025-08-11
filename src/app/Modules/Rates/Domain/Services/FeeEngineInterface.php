<?php

namespace App\Modules\Rates\Domain\Services;

interface FeeEngineInterface
{
    public function compute(string $serviceKey, string $amountUsd): string;
}
