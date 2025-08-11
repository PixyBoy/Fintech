<?php

namespace App\Modules\Rates\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;

class FeeRuleModel extends Model
{
    protected $table = 'fee_rules';

    protected $fillable = [
        'service_key','from_amount','to_amount','fee_type','value','is_active'
    ];
}
