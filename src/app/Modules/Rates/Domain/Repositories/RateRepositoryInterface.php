<?php

namespace App\Modules\Rates\Domain\Repositories;

use App\Modules\Rates\Domain\Entities\Rate;

interface RateRepositoryInterface
{
    public function latest(): ?Rate;

    public function upsert(Rate $rate): Rate;
}
