<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Add2faColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('users', function (Blueprint $table) {
        //     $table->text('google_2fa_secret')
        //             ->after('password')
        //             ->nullable();

        //     $table->boolean('google_2fa_status')
        //             ->after('google_2fa_secret')
        //             ->default(0);

        //     $table->text('2fa_recovery_codes')
        //             ->after('google_2fa_status')
        //             ->nullable();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('users', function (Blueprint $table) {
        //     $table->dropColumn('google_2fa_secret', 'google_2fa_status', '2fa_recovery_codes');
        // });
    }
}
