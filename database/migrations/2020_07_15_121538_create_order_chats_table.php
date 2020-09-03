<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderChatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_chats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('reciver_id')->nullable();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->text('message')->nullable();
            $table->string('file_path')->nullable();
            $table->string('type')->nullable();
            $table->string('file_type')->nullable();
            $table->string('is_reported')->nullable();
            $table->string('is_spammed')->nullable();
            
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            
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
        Schema::dropIfExists('order_chats');
    }
}
