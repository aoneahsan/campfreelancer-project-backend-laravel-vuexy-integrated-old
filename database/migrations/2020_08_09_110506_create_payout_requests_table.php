<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayoutRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payout_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('user_balance_before_placing_request')->nullable();
            $table->unsignedBigInteger('user_balance_after_placing_request')->nullable();
            $table->unsignedBigInteger('payout_request_amount')->nullable();
            $table->dateTime('payout_request_created_at')->nullable();
            $table->dateTime('payout_request_completed_at')->nullable();
            $table->dateTime('payout_request_rejected_at')->nullable();
            $table->string('payout_method')->nullable();
            $table->string('status')->nullable();
            $table->longText('paypal_response')->nullable();
            $table->longText('payoneer_response')->nullable();
            
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
        Schema::dropIfExists('payout_requests');
    }
}
