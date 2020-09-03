<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('reciver_id')->nullable();
            $table->text('message')->nullable();
            $table->longText('custom_offer_data')->nullable();
            $table->string('custom_offer_status')->nullable();
            $table->string('file_name')->nullable();
            $table->string('is_reported')->nullable();
            $table->string('is_spammed')->nullable();
            $table->string('type')->nullable();
            $table->string('file_type')->nullable();
            $table->boolean('is_read')->default(false)->nullable();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('reciver_id')->references('id')->on('users')->onDelete('cascade');

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
        Schema::dropIfExists('messages');
    }
}
