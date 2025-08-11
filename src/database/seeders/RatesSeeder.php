<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RatesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('rates')->insert([
            'base_currency' => 'IRR',
            'usd_buy' => 560000,
            'usd_sell' => 580000,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
