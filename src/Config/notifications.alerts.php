<?php

return [
    'seat_market_newOrder' => [
        'label' => 'Seat BUSA MART - New Order',
        'handlers' => [
            'slack' => \Helious\SeatBusaMarket\Notifications\NewOrder::class,
        ],
    ]
];