<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->uuid('trx');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->char('currency', 3);
            $table->decimal('amount', 15, 2);
            $table->decimal('fixed_charge', 15, 2)->default(0);
            $table->decimal('percent_charge_rate', 3, 2)->default(0);
            $table->decimal('percent_charge', 15, 2)->default(0);
            $table->decimal('charge', 15, 2)->default(0);
            $table->decimal('initial_balance', 15, 8)->default(0);
            $table->tinyInteger('added')->default(1);
            $table->string('description')->nullable();
            $table->nullableMorphs('transactionable');
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
        Schema::dropIfExists('transactions');
    }
}
