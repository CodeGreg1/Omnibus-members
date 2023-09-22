<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->longText('description')->nullable();
            $table->string('type');
            $table->string('slug')->unique();
            $table->string('status');
            $table->string('page_title', 70)->nullable();
            $table->text('page_description', 320)->nullable();
            $table->tinyInteger('has_breadcrumb')->default(0);

            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
        });

        Schema::create('page_contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained()->cascadeOnDelete();
            $table->longText('body');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('page_sections', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('order');
            $table->foreignId('page_id')->constrained()->cascadeOnDelete();
            $table->string('heading')->nullable();
            $table->string('sub_heading')->nullable();
            $table->string('description')->nullable();
            $table->string('background_color')->nullable();
            $table->string('media_id')->constrained()->cascadeOnDelete()->nullable();
            $table->string('template');
            $table->json('data')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('page_scriptags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('url')->unique();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('page_styles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('url')->unique();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pages');
        Schema::dropIfExists('page_contents');
        Schema::dropIfExists('page_sections');
        Schema::dropIfExists('page_scriptags');
        Schema::dropIfExists('page_styles');
    }
}
