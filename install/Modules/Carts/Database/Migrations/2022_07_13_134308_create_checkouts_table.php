<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCheckoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checkouts', function (Blueprint $table) {
            $table->char('id', 40)->primary();
            $table->string('mode'); // subscription, payment
            $table->string('gateway');
            $table->string('cancel_url');
            $table->string('success_url');
            $table->foreignId('customer_id');
            $table->foreignId('promo_code_id')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->foreignId('shipping_address_id')->nullable();
            $table->foreignId('billing_address_id')->nullable();
            $table->foreignId('shipping_rate_id')->nullable();
            $table->tinyInteger('allow_promo_code')->default(0);
            $table->tinyInteger('collect_shipping_address')->default(0);
            $table->tinyInteger('collect_billing_address')->default(1);
            $table->tinyInteger('allow_shipping_method')->default(0);
            $table->tinyInteger('collect_phone_number')->default(0);
            $table->text('confirm_page_message')->nullable();
            $table->json('metadata')->nullable();
            $table->nullableMorphs('checkouted');

            $table->timestamps();
        });

        Schema::create('checkout_items', function (Blueprint $table) {
            $table->id();
            $table->string('checkout_id');
            $table->bigInteger('quantity')->default(1);
            $table->morphs('checkoutable');

            $table->timestamps();

            $table->foreign('checkout_id')->cascadeOnDelete()->references('id')->on('checkouts');
        });

        Schema::create('checkout_item_tax_rate', function (Blueprint $table) {
            $table->id();
            $table->foreignId('checkout_item_id')->constrained()->onDelete('cascade');
            $table->foreignId('tax_rate_id')->constrained();

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
        Schema::dropIfExists('checkouts');
    }
}