<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAffiliateReferralCommissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('affiliate_referral_commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('referral_id')->constrained('affiliate_referral_levels')->cascadeOnDelete();
            $table->decimal('amount')->default(0);
            $table->char('currency', 3);

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
        Schema::dropIfExists('affiliate_referral_commissions');
    }
}
