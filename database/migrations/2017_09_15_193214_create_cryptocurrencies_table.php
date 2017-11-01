<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCryptocurrenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cryptocurrencies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('symbol', 5);
            $table->string('logo_url');
            $table->timestamp('last_block_update')->useCurrent();
            $table->decimal('last_btc_price', 16, 8)->default(0);
            $table->decimal('last_usd_price', 16, 2)->default(0);
            $table->decimal('last_eur_price', 16, 2)->default(0);
            $table->integer('confirmations');
            $table->string('block_explorer');
            $table->string('tx_explorer');
            $table->string('uri');
            $table->boolean('maintenance')->default(false);
            $table->integer('wallet_port');
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
        Schema::dropIfExists('cryptocurrencies');
    }
}
