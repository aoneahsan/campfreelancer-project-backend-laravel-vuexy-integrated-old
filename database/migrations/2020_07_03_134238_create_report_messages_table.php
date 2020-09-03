<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('message_sender_id')->nullable();
            $table->unsignedBigInteger('message_id')->nullable();
            $table->text('reason')->nullable();
            $table->string('is_spammed')->nullable();
            $table->string('type')->nullable(); // report|spam

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('message_sender_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('message_id')->references('id')->on('messages')->onDelete('cascade');

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
        Schema::dropIfExists('report_messages');
    }
}
