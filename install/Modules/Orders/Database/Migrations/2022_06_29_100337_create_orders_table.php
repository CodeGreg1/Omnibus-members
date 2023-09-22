<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id');
            $table->char('cart_token')->index()->unique();
            $table->char('currency', 3)->default(config('availablecurrencies.default', 'USD'));
            $table->string('gateway')->nullable();
            $table->decimal('total_discounts', 8, 2)->default(0);
            $table->decimal('shipping_amount', 8, 2)->default(0);
            $table->decimal('total_tax', 8, 2)->default(0);
            $table->decimal('subtotal_price', 8, 2)->default(0);
            $table->decimal('total_price', 8, 2)->default(0);
            $table->timestamp('cancelled_at')->nullable();
            $table->string('cancel_reason')->nullable();
            $table->foreignId('billing_address_id')->nullable();
            $table->foreignId('shipping_address_id')->nullable();
            $table->tinyInteger('paid');
            $table->string('status');
            $table->text('note')->nullable();
            $table->string('phone')->nullable();
            $table->tinyInteger('live')->default(0);
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id');
            $table->decimal('price', 8, 2)->default(0);
            $table->decimal('quantity', 8, 2)->default(1);
            $table->decimal('total_tax', 8, 2)->default(0);
            $table->decimal('total_discounts', 8, 2)->default(0);
            $table->string('title');
            $table->json('attributes')->nullable();
            $table->morphs('orderable');
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
        Schema::dropIfExists('orders');
        Schema::dropIfExists('order_items');
    }
}