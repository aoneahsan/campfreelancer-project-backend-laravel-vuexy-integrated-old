<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGigRequirementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gig_requirements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gig_id')->nullable();
            $table->text('title')->nullable();
            $table->longText('description')->nullable();
            $table->text('file_name')->nullable();
            $table->string('is_required')->nullable();

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
        Schema::dropIfExists('gig_requirements');
    }
}
