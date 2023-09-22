<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->char('currency', 3)->default(config('availablecurrencies.default', 'USD'));
            $table->double('price', 8, 2)->default(0);
            $table->string('title');
            $table->string('brand')->nullable();
            $table->string('category')->nullable();
            $table->string('description')->nullable();
            $table->double('rating', 4, 2)->default(0);
            $table->integer('stock')->default(0);

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
        Schema::dropIfExists('products');
    }
}