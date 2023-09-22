<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAffiliatCommissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('affiliate_commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('affiliate_user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('reffered_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('amount');
            $table->char('currency', 3);
            $table->decimal('rate');
            $table->string('type');
            $table->string('status');
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('approve_on_end')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->string('rejected_reason')->nullable();

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
        Schema::dropIfExists('affiliat_commissions');
    }
}
