<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGigAnalyticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gig_analytics', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gig_id')->nullable();
            $table->string('type')->nullable();

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
        Schema::dropIfExists('gig_analytics');
    }
}
