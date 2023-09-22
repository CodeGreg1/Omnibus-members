<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAuthyColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('authy_status')->nullable()->after('password');
            $table->string('authy_id', 25)->nullable()->after('authy_status');
            $table->string('authy_country_code', 10)->nullable()->after('authy_id');
            $table->string('authy_phone')->nullable()->after('authy_country_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('authy_status');
            $table->dropColumn('authy_id', 25);
            $table->dropColumn('authy_country_code', 10);
            $table->dropColumn('authy_phone');
        });
    }
}
