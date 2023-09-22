<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
                        
            $table->string('name');            
            $table->longText('description')->nullable();            
            $table->string('color')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unsignedBigInteger('category_type_id');

			$table->foreign('category_type_id', 'category_types_fk_9670694')
                ->references('id')
                ->on('category_types')
                ->onDelete('cascade');

            $table->unsignedBigInteger('parent_id')->nullable();

            $table->foreign('parent_id', 'category_types_fk_4570842')
                ->references('id')
                ->on('category_types')
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
        Schema::dropIfExists('categories');
    }
}
