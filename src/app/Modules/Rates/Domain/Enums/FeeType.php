<?php

namespace App\Modules\Rates\Domain\Enums;

enum FeeType: string
{
    case Fixed = 'fixed';
    case Percent = 'percent';
}
