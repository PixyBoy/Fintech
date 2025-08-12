<?php

namespace App\Modules\PayForMe\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;

class PayForMeRequestModel extends Model
{
    protected $table = 'payforme_requests';

    protected $fillable = [
        'user_id', 'request_code', 'target_url', 'amount_usd',
        'notes', 'attachments', 'quote_snapshot', 'order_id', 'status'
    ];

    protected $casts = [
        'attachments' => 'array',
        'quote_snapshot' => 'array',
        'amount_usd' => 'float',
    ];
}
