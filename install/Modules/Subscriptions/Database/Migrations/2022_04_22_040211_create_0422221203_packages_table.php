<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Create0422221203PackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('package_terms', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description')->nullable();
            $table->enum('interval', ['day', 'week', 'month', 'year'])->default('day');
            $table->integer('interval_count')->default(1);
            $table->tinyInteger('enabled')->default(0);
            $table->timestamps();
        });

        Schema::create('package_features', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description')->nullable();
            $table->integer('ordering')->default(0);
            $table->timestamps();
        });

        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('primary_heading')->nullable();
            $table->string('secondary_heading')->nullable();
            $table->tinyInteger('live')->default(0);
            $table->timestamps();
        });

        Schema::create('package_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained()->onDelete('cascade');
            $table->foreignId('package_term_id')->nullable();
            $table->char('currency', 3)->default(config('availablecurrencies.default', 'USD'));
            $table->decimal('price', 8, 2)->default(0);
            $table->decimal('compare_at_price')->default(0);
            $table->enum('type', ['recurring', 'onetime']);
            $table->integer('trial_days_count')->default(0);
            $table->tinyInteger('enabled')->default(1);
            $table->tinyInteger('live')->default(0);

            $table->timestamps();
        });

        Schema::create('package_package_feature', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained()->onDelete('cascade');
            $table->foreignId('package_feature_id');
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('package_module_limits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained()->onDelete('cascade');
            $table->foreignId('permission_id')->constrained()->onDelete('cascade');
            $table->integer('limit')->nullable();
            $table->string('term');
            $table->timestamps();
        });

        Schema::create('user_module_limit_counters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('limit_id')->constrained('package_module_limits')->onDelete('cascade');
            $table->integer('count')->default(0);
            $table->date('date');
            $table->timestamps();
        });

        DB::table('package_terms')->insert([
            ['title' => 'Daily', 'description' => 'Daily', 'interval' => 'day', 'interval_count' => 1, 'enabled' => 1],
            ['title' => 'Weekly', 'description' => 'Weekly', 'interval' => 'day', 'interval_count' => 7, 'enabled' => 1],
            ['title' => 'Monthly', 'description' => 'Monthly', 'interval' => 'month', 'interval_count' => 1, 'enabled' => 1],
            ['title' => 'Quarterly', 'description' => 'Quarterly', 'interval' => 'month', 'interval_count' => 3, 'enabled' => 1],
            ['title' => 'Yearly', 'description' => 'Yearly', 'interval' => 'year', 'interval_count' => 1, 'enabled' => 1],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::dropIfExists('package_terms');
        Schema::dropIfExists('package_features');
        Schema::dropIfExists('packages');
        Schema::dropIfExists('package_prices');
        Schema::dropIfExists('package_package_feature');
        Schema::dropIfExists('package_module_limits');
        Schema::dropIfExists('user_module_limit_counter');
        Schema::dropIfExists('gateway_packages');
        Schema::dropIfExists('gateway_package_prices');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}