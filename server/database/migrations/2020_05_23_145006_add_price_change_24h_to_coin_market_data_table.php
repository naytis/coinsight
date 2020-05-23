<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPriceChange24hToCoinMarketDataTable extends Migration
{
    public function up()
    {
        Schema::table('coin_market_data', function (Blueprint $table) {
            $table->float('price_change_24h')
                ->nullable()
                ->after('price');
        });
    }

    public function down()
    {
        Schema::table('coin_market_data', function (Blueprint $table) {
            $table->dropColumn('price_change_24h');
        });
    }
}
