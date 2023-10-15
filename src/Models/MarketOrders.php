<?php

namespace Helious\SeatBusaMarket\Models;

use Illuminate\Database\Eloquent\Model;

class MarketOrders extends Model
{

    protected $table = 'seat_busa_market_orders';

    protected $fillable = [
        'user_id',
        'order_json',
        'estimated_price',
        'appraised_price',
        'janice_link',
        'status'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function creator()
    {
        return $this->hasOne('Seat\Web\Models\User', 'id', 'user_id');
    }
}