<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('p_transactions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('payer_name');
            $table->string('payer_ip');
            $table->string('payment_timestamp');
            $table->string('card_brand');
            $table->string('card_country');
            $table->string('card_zip');
            $table->string('card_exp');
            $table->string('card_id');
            $table->string('card_last4');
            $table->unsignedInteger('order_id')->references('id')->on('food');;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('p_transactions');
    }
}
