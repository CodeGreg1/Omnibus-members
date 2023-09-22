<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use function GuzzleHttp\default_ca_bundle;

use Illuminate\Database\Migrations\Migration;

class CreateShippingRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_rates', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->decimal('price', 8, 2)->default(0);
            $table->char('currency', 3)->default(config('availablecurrencies.default', 'USD'));
            $table->tinyInteger('active')->default(1);
            $table->tinyInteger('default')->default(0);
            $table->json('metadata')->nullable();
            $table->tinyInteger('live')->default(0);

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
        Schema::dropIfExists('shipping_rates');
    }
}