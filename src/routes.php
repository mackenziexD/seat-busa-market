<?php

Route::group([
    
    'namespace' => 'Helious\SeatBusaMarket\Http\Controllers\Market',
    'prefix' => 'market',
    'middleware' => [
        'web',
        'auth',
    ],
], function()
{

    Route::get('/index', [
        'uses' => 'MarketController@index',
        'as' => 'seat-busa-market.index'
    ]);

    Route::get('/orders', [
        'uses' => 'MarketController@orders',
        'as' => 'seat-busa-market.orders'
    ]);

});