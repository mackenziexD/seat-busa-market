<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SeatBusaMarketInitialDeployment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seat_busa_market_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->longText('order_json');
            $table->bigInteger('estimated_price');
            $table->bigInteger('appraised_price')->nullable();
            $table->string('janice_link')->nullable();
            $table->string('status')->default('Outstanding');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seat_busa_market_orders');
    }
}