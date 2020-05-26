<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdditionalPercentagesAndMaxSupplyToCoinMarketDataTable extends Migration
{
    public function up()
    {
        Schema::table('coin_market_data', function (Blueprint $table) {
            $table->float('price_change_1h')->nullable();
            $table->float('price_change_7d')->nullable();
            $table->float('price_change_30d')->nullable();
            $table->float('price_change_1y')->nullable();
            $table->float('max_supply')->nullable();
        });
    }

    public function down()
    {
        Schema::table('coin_market_data', function (Blueprint $table) {
            $table->dropColumn([
                'price_change_1h',
                'price_change_7d',
                'price_change_30d',
                'price_change_1y',
                'max_supply',
            ]);
        });
    }
}
