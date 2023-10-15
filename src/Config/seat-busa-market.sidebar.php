<?php

return [
    'market' => [
        'name' => 'BUSA Market',
        'icon' => 'fa fa-shopping-cart',
        'route_segment' => 'market',
        'route' => '',
        'entries' => [
            [
                'name' => 'Market',
                'icon' => 'fa fa-money-bill',
                'route' => 'seat-busa-market.index',
                'permission' => [
                    'seat-busa-market.market',
                    'seat-busa-market.orders',
                ],
            ],
            [
                'name' => 'Orders',
                'icon' => 'fa fa-list',
                'route' => 'seat-busa-market.orders',
                'permission' => [
                    'seat-busa-market.orders',
                ],
            ],
        ],
    ],
];