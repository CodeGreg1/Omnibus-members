<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->char('currency', 3)->nullable();
            $table->decimal('amount', 8, 2);
            $table->string('amount_type');
            $table->integer('billing_duration')->default(0);
            $table->bigInteger('redeem_date_end')->nullable();
            $table->integer('redeem_limit_count')->nullable();
            $table->bigInteger('times_redeemed')->default(0);
            $table->tinyInteger('live')->default(0);
            $table->timestamps();
        });

        Schema::create('promo_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')->constrained()->cascadeOnDelete();
            $table->string('code');
            $table->tinyInteger('active')->default(1);
            $table->bigInteger('expires_at')->nullable();
            $table->integer('max_redemptions')->nullable();
            $table->bigInteger('times_redeemed')->default(0);
            $table->tinyInteger('live')->default(0);
            $table->timestamps();
        });

        Schema::create('promo_code_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promo_code_id');
            $table->foreignId('user_id');
            $table->timestamps();
        });

        Schema::create('discountables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')->constrained()->cascadeOnDelete();
            $table->morphs('discountable');
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
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::dropIfExists('coupons');
        Schema::dropIfExists('promo_codes');
        Schema::dropIfExists('promo_code_user');
        Schema::dropIfExists('discountables');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}