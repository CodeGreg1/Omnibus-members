<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAvailableCurrenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('available_currencies', function (Blueprint $table) {
            $table->id();

            $table->string('name', 100)->unique();
            $table->string('symbol', 10);
            $table->string('code', 10);
            $table->decimal('exchange_rate', 15, 6);
            $table->boolean('status')->default(0)->nullable();
            $table->timestamps();

            $table->unsignedBigInteger('currency_id');

            $table->foreign('currency_id', 'currencies_fk_5732696')
                ->references('id')
                ->on('currencies')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('available_currencies');
    }
}
