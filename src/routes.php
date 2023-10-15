<?php

Route::group([
    'namespace' => 'Helious\SeatBusaMarket\Http\Controllers\Market',
    'prefix' => 'market',
    'middleware' => [
        'web',
        'auth',
    ],
], function () {
    Route::group([
        'middleware' => 'can:seat-busa-market.market',
    ], function () {
        Route::get('/index', [
            'uses' => 'MarketController@index',
            'as' => 'seat-busa-market.index',
        ]);

        Route::post('/index', [
            'uses' => 'MarketController@CreateOrder',
            'as' => 'seat-busa-market.create-order',
        ]);
    });

    Route::group([
        'middleware' => 'can:seat-busa-market.orders',
    ], function () {
        Route::get('/orders', [
            'uses' => 'MarketController@orders',
            'as' => 'seat-busa-market.orders',
        ]);

        Route::get('/order/{id}', [
            'uses' => 'MarketController@order',
            'as' => 'seat-busa-market.order',
        ]);

        Route::post('/order/{id}', [
            'uses' => 'MarketController@completeOrder',
            'as' => 'seat-busa-market.completeOrder',
        ]);
    });
});
