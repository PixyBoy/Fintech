<?php

namespace App\Modules\Rates\Listeners;

use App\Modules\Rates\Events\FeeRulesChanged;
use Illuminate\Support\Facades\Cache;

class ClearFeeRulesCache
{
    public function handle(FeeRulesChanged $event): void
    {
        Cache::forget("fees:{$event->serviceKey}");
    }
}
