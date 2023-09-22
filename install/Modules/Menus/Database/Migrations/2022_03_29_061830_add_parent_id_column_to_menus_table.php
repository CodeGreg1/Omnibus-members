<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddParentIdColumnToMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_id')
                ->after('id')
                ->nullable()
                ->comment('Child menu by other language.');

            $table->foreign('parent_id')
                ->references('id')
                ->on('menus')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();

        Schema::table('menus', function (Blueprint $table) {
            $table->dropForeign('menus_parent_id_foreign');
            $table->dropColumn('parent_id');
        });

        Schema::enableForeignKeyConstraints();
    }
}
