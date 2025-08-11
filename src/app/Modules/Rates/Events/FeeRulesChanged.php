<?php

namespace App\Modules\Rates\Events;

class FeeRulesChanged
{
    public function __construct(public string $serviceKey)
    {
    }
}
