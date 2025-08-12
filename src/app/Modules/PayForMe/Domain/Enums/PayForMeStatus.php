<?php

namespace App\Modules\PayForMe\Domain\Enums;

enum PayForMeStatus: string
{
    case Pending = 'pending';
    case Paid = 'paid';
    case Processing = 'processing';
    case Done = 'done';
    case Refunded = 'refunded';
    case Failed = 'failed';
}
