<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGigCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gig_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->text('title')->nullable();
            $table->text('slug')->nullable();
            $table->longText('description')->nullable();
            $table->string('icon_file_path')->nullable();
            $table->string('image_file_path')->nullable();
            $table->string('banner_file_path')->nullable();
            $table->string('video_file_path')->nullable();
            $table->integer('freelancers_increment')->nullable(); // for home page slider subheading
            $table->string('sort_order')->nullable();
            $table->boolean('is_visible')->default(true)->nullable();
            $table->boolean('is_parent')->default(false)->nullable();
            $table->boolean('is_popular')->default(false)->nullable();
            $table->boolean('header_menu_item')->default(false)->nullable();
            
            $table->foreign('parent_id')->references('id')->on('gig_categories')->onDelete('cascade');

            $table->softDeletes();
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
        Schema::dropIfExists('gig_categories');
    }
}
