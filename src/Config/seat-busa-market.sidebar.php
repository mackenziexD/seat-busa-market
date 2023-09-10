<?php

return [
    'market' => [
        'name' => 'BUSA Market',
        'icon' => 'fas fa-handshake',
        'route_segment' => 'market',
        'entries' => [
            [
                'name' => 'Market',
                'icon' => 'fab fa-shopping-cart',
                'route' => 'seat-market.market.index',
                'permission' => [
                    'seat-busa-market.market',
                    'seat-busa-market.orders',
                ],
            ],
            [
                'name' => 'Orders',
                'icon' => 'fab fa-list',
                'route' => 'seat-market.orders.index',
                'permission' => [
                    'seat-busa-market.orders',
                ],
            ],
        ],
    ],
];