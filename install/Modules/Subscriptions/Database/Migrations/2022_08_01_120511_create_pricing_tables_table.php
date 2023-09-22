<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePricingTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pricing_tables', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('description')->nullable();
            $table->tinyInteger('active')->default(0);
            $table->tinyInteger('live')->default(0);
            $table->timestamps();
        });

        Schema::create('pricing_table_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pricing_table_id')->constrained()->onDelete('cascade');
            $table->foreignId('package_price_id')->constrained()->onDelete('cascade');
            $table->tinyInteger('featured')->default(0);
            $table->tinyInteger('allow_promo_code')->default(0);
            $table->tinyInteger('order')->default(1);
            $table->text('confirm_page_message')->nullable();
            $table->string('button_label')->nullable();
            $table->tinyText('button_link')->nullable();
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
        Schema::dropIfExists('pricing_tables');
    }
}