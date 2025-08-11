<?php

namespace App\Modules\Orders\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;

class OrderModel extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'user_id','service_key','currency','amount_usd','fee_usd','subtotal_usd','rate_used','total_irr','meta','quote_breakdown','status'
    ];

    protected $casts = [
        'meta' => 'array',
        'quote_breakdown' => 'array',
    ];

    public function items()
    {
        return $this->hasMany(OrderItemModel::class, 'order_id');
    }
}
