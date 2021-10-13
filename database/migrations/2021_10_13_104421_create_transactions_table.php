<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_number')->unsigned();
            $table->bigInteger('stock_id')->unsigned();
            $table->bigInteger('qty')->unsigned();
            $table->bigInteger('user_id')->default(1)->unsigned(); // this is for who made the sale, will not implement on this trial proj
            $table->timestamps();
            $table->index(['order_number', 'stock_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
