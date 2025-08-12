<?php
return [
    'routes_prefix' => 'modules/payforme',
    'quote' => [
        'use_stub' => true,
        'stub' => [
            'usd_sell' => 580000,
            'fee_rules' => [
                ['from' => 0, 'to' => 50, 'type' => 'percent', 'value' => 5],
                ['from' => 50, 'to' => 200, 'type' => 'percent', 'value' => 3],
                ['from' => 200, 'to' => 999999, 'type' => 'fixed', 'value' => 4],
            ],
        ],
    ],
];
