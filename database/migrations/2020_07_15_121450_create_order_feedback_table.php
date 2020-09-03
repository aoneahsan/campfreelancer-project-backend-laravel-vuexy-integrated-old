<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderFeedbackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_feedback', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('buyer_id')->nullable();
            $table->unsignedBigInteger('seller_id')->nullable();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('gig_id')->nullable();
            $table->dateTime('buyer_feedback_at')->nullable();
            $table->string('buyer_feedback')->nullable();
            $table->string('buyer_satisfaction_level')->nullable();
            $table->string('buyer_rating_sellerCommunication')->nullable();
            $table->string('buyer_rating_serviceAsDescribed')->nullable();
            $table->string('buyer_rating_sellerRecommended')->nullable();
            $table->string('buyer_rating')->nullable();
            $table->dateTime('seller_feedback_at')->nullable();
            $table->string('seller_feedback')->nullable();
            $table->string('seller_rating_buyerCommunication')->nullable();
            $table->string('seller_rating_buyerRecommended')->nullable();
            $table->string('seller_rating')->nullable();

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
        Schema::dropIfExists('order_feedback');
    }
}
