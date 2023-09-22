<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use function GuzzleHttp\default_ca_bundle;

class CreateCartItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->char('cart_item_id', 40)->index();
            $table->foreignId(config('carts.storage.stores.db.cart_key'))->nullable();
            $table->string('instance')->index();
            $table->string('name');
            $table->decimal('price', 8, 2)->default(0);
            $table->integer('quantity')->default(1);

            $table->morphs('purchasable');
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
        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('cart_item_attributes');
        Schema::dropIfExists('cart_item_conditions');
    }
}