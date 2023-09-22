<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Create0422221203SubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('ref_profile_id')->unique();
            $table->morphs('subscribable');
            $table->string('gateway')->index();
            $table->string('name');
            $table->string('description')->nullable();
            $table->tinyInteger('recurring')->default(1);
            $table->tinyInteger('trialing')->default(0);
            $table->dateTime('trial_ends_at')->nullable();
            $table->dateTime('starts_at')->nullable();
            $table->dateTime('ends_at')->nullable();
            $table->dateTime('cancels_at')->nullable();
            $table->dateTime('canceled_at')->nullable();
            $table->json('meta')->nullable();
            $table->text('note')->nullable();
            $table->tinyInteger('live')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create(
            'subscription_items',
            function (Blueprint $table) {
                $table->id();
                $table->foreignId('subscription_id')->constrained()->onDelete('cascade');
                $table->foreignId('package_price_id');
                $table->integer('quantity')->default(1);
                $table->char('currency', 3)->default(config('availablecurrencies.default', 'USD'));
                $table->decimal('total', 8, 2)->default(0);
                $table->timestamps();
                $table->softDeletes();

                // Indexes
                $table->foreign('package_price_id')
                    ->references('id')
                    ->on('package_prices')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::dropIfExists('subscriptions');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}