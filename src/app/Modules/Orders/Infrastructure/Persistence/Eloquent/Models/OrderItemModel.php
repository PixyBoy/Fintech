<?php

namespace App\Modules\Orders\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItemModel extends Model
{
    protected $table = 'order_items';

    protected $fillable = [
        'order_id','sku','title','unit_price_usd','qty','line_total_usd','meta'
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public function order()
    {
        return $this->belongsTo(OrderModel::class, 'order_id');
    }
}
