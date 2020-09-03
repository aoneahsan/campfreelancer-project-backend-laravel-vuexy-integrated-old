<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGigGalleriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gig_galleries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gig_id')->nullable();
            $table->string('file_type')->nullable();
            $table->string('file_number')->nullable();
            $table->text('file_name')->nullable();

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
        Schema::dropIfExists('gig_galleries');
    }
}
