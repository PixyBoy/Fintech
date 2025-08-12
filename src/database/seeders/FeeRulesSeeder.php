<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FeeRulesSeeder extends Seeder
{
    public function run(): void
    {
        $rules = [
            ['service_key' => 'payforme', 'from_amount' => 0, 'to_amount' => 50, 'fee_type' => 'percent', 'value' => 5, 'is_active' => true],
            ['service_key' => 'payforme', 'from_amount' => 50, 'to_amount' => 200, 'fee_type' => 'percent', 'value' => 3, 'is_active' => true],
            ['service_key' => 'payforme', 'from_amount' => 200, 'to_amount' => 999999, 'fee_type' => 'fixed', 'value' => 4, 'is_active' => true],
        ];

        foreach ($rules as $rule) {
            DB::table('fee_rules')->insert(array_merge($rule, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
