<?php

namespace App\Modules\Rates\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;

class RateModel extends Model
{
    protected $table = 'rates';

    protected $fillable = ['base_currency','usd_buy','usd_sell'];
}
