<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('reciver_id')->nullable();
            $table->boolean('is_favorite')->default(false)->nullable();
            $table->boolean('is_archived')->default(false)->nullable();
            $table->boolean('is_spammed')->default(false)->nullable();
            $table->string('label_type')->nullable();
            $table->string('latest_message')->nullable();
            $table->string('message_type')->nullable();
            $table->string('message_sender_id')->nullable();
            $table->string('message_send_at')->nullable();
            
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
        Schema::dropIfExists('chat_users');
    }
}
