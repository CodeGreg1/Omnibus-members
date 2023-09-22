<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackagePriceGatewayTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('package_gateways', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('gateway')->index();
            $table->foreignId('package_id');
            $table->tinyInteger('live')->default(0);
        });

        Schema::create('package_price_gateways', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('gateway')->index();
            $table->foreignId('package_price_id');
            $table->tinyInteger('live')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('package_gateways');
        Schema::dropIfExists('package_price_gateway');
    }
}
