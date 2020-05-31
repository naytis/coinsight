<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCoinGeckoIdToCoinsTable extends Migration
{
    public function up()
    {
        Schema::table('coins', function (Blueprint $table) {
            $table->string('coin_gecko_id')->nullable();
        });
    }

    public function down()
    {
        Schema::table('coins', function (Blueprint $table) {
            $table->dropColumn('coin_gecko_id');
        });
    }
}
