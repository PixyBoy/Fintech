<?php

namespace App\Modules\Orders\Domain\Enums;

enum OrderStatus: string
{
    case Pending = 'pending';
    case Paid = 'paid';
    case Processing = 'processing';
    case Done = 'done';
    case Refunded = 'refunded';
    case Failed = 'failed';
}
