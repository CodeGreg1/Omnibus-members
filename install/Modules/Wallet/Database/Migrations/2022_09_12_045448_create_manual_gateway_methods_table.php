<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateManualGatewayMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manual_gateway_methods', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('name');
            $table->decimal('min_limit', 8, 2);
            $table->decimal('max_limit', 8, 2);
            $table->string('delay')->nullable();
            $table->decimal('fixed_charge', 8, 2)->nullable();
            $table->decimal('percent_charge', 3, 2)->nullable();
            $table->char('currency', 3)->nullable();
            $table->json('user_data')->nullable();
            $table->longText('instructions')->nullable();
            $table->tinyInteger('status')->default(0);

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
        Schema::dropIfExists('manual_gateway_methods');
    }
}
