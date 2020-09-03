<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserGigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_gigs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('subcategory_id')->nullable();
            $table->unsignedBigInteger('service_type_id')->nullable();
            $table->text('title')->nullable();
            $table->text('slug')->nullable();
            $table->longText('description')->nullable();
            $table->string('gig_type')->nullable();
            $table->string('hourly_rate')->nullable();
            $table->text('tags')->nullable();
            $table->string('status')->nullable();
            $table->string('is_three_packages_mode_on')->nullable();
            $table->string('is_extra_fast_delivery_on')->nullable();
            
            $table->boolean('is_home_map_feature_item')->default(false)->nullable();
            $table->boolean('is_home_expert_section_item')->default(false)->nullable();

            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // $table->foreign('category_id')->references('id')->on('gig_categories')->onDelete('cascade');
            // $table->foreign('subcategory_id')->references('id')->on('gig_categories')->onDelete('cascade');
            // $table->foreign('service_type_id')->references('id')->on('gig_service_types')->onDelete('cascade');
            
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
        Schema::dropIfExists('user_gigs');
    }
}
