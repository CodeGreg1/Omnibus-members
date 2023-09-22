<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('page_title', 70)->nullable();
            $table->text('page_description', 320)->nullable();
            $table->string('media_id')->constrained()->cascadeOnDelete()->nullable();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->longText('content');
            $table->string('status');
            $table->bigInteger('views')->default(0);
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamp('modified_at')->nullable();

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
        Schema::dropIfExists('blogs');
    }
}
