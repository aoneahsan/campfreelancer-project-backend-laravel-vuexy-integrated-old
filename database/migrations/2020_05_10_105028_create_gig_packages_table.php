<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGigPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gig_packages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gig_id')->nullable();
            $table->text('title')->nullable();
            $table->longText('description')->nullable();
            // $table->string('time')->nullable();
            $table->integer('time')->nullable();
            $table->string('revisions')->nullable();
            $table->unsignedBigInteger('price')->nullable();
            $table->boolean('is_hourly')->nullable();
            $table->boolean('extra_fast_delivery_enabled')->nullable();
            $table->boolean('extra_fast_delivery_time')->nullable();
            $table->boolean('extra_fast_delivery_price')->nullable();
            $table->string('sort_order')->nullable();
            $table->string('is_visible')->nullable();
            
            $table->foreign('gig_id')->references('id')->on('user_gigs')->onDelete('cascade');

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
        Schema::dropIfExists('gig_packages');
    }
}
