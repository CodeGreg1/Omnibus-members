<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepositsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deposits', function (Blueprint $table) {
            $table->id();
            $table->uuid('trx')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('method_id')->nullable()->constrained('manual_gateway_methods');
            $table->string('gateway')->nullable();
            $table->decimal('amount', 15, 2);
            $table->decimal('fixed_charge', 15, 2)->default(0);
            $table->decimal('percent_charge_rate', 3, 2)->default(0);
            $table->decimal('percent_charge', 15, 2)->default(0);
            $table->decimal('charge', 15, 2);
            $table->char('currency', 3);
            $table->tinyInteger('status');
            $table->json('details')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->string('reject_reason')->nullable();

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
        Schema::dropIfExists('deposits');
    }
}
