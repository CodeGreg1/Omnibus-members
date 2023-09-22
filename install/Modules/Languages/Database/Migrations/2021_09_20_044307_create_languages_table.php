<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLanguagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->string('title');            
            $table->string('code', 2)->unique();            
            $table->string('direction');            
            $table->boolean('active')->default(0)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unsignedInteger('flag_id');

            $table->foreign('flag_id', 'countries_fk_11839108')
                ->references('id')
                ->on('countries')
                ->onDelete('cascade');
        });

        // DB::table('languages')->insert([
        //     'title' => 'English',
        //     'code' => 'en',
        //     'direction' => 'ltr',
        //     'active' => 1,
        //     'created_at' => now(),
        //     'updated_at' => now(),
        //     'flag_id' => 840
        // ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('languages');
    }
}
