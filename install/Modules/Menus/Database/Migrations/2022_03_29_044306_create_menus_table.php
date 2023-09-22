<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('language_id');
            $table->string('type');
            $table->string('name')->unique();
            $table->string('description')->nullable();
            $table->string('class')->commet('Your custom styles into class.')->nullable();
            $table->timestamps();

            $table->foreign('language_id')
                ->references('id')
                ->on('languages')
                ->onDelete('cascade');
        });

        Schema::create('menu_links', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('menu_id');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->integer('ordering');
            $table->string('icon')->nullable();
            $table->string('label');
            $table->string('link');
            $table->string('target', 10)->nullable();
            $table->string('class')->nullable();
            $table->boolean('status')->default(1);
            $table->string('type')->default('Default');
            $table->timestamps();

            $table->foreign('menu_id')
                ->references('id')
                ->on('menus')
                ->onDelete('cascade');

            $table->foreign('parent_id')
                ->references('id')
                ->on('menu_links')
                ->onDelete('cascade');
        });

        Schema::create('menu_roles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('menu_link_id');
            $table->unsignedBigInteger('role_id');

            $table->foreign('menu_link_id')
                ->references('id')
                ->on('menu_links')
                ->onDelete('cascade');

            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menus');
        Schema::dropIfExists('menu_links');
        Schema::dropIfExists('menu_roles');
    }
}
