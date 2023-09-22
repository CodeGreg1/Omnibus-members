<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAffiliateReferralLevelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('affiliate_referral_levels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('affiliate_user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('reffered_id')->constrained('users')->cascadeOnDelete();
            $table->integer('level');

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
        Schema::dropIfExists('affiliate_referral_levels');
    }
}
