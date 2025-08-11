<?php

namespace App\Modules\Rates\Listeners;

use App\Modules\Rates\Events\RatesUpdated;
use Illuminate\Support\Facades\Cache;

class ClearRatesCache
{
    public function handle(RatesUpdated $event): void
    {
        Cache::forget('rates:current');
    }
}
