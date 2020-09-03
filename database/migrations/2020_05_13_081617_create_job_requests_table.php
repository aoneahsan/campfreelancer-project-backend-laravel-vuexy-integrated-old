<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('subcategory_id')->nullable();
            $table->unsignedBigInteger('service_type_id')->nullable();
            $table->longText('description')->nullable();
            $table->text('file_name')->nullable();
            $table->string('time')->nullable();
            $table->unsignedBigInteger('price')->nullable();
            $table->boolean('is_hourly')->default(false)->nullable();
            $table->string('buyer_location')->nullable();
            $table->string('request_type')->default('basic')->nullable();
            $table->string('status')->default('pending_approval')->nullable();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

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
        Schema::dropIfExists('job_requests');
    }
}
