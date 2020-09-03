<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTransactionLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_transaction_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('order_id');
            $table->string('order_number')->nullable();
            $table->string('transaction_log_type')->nullable(); // order_revenue | funds_cleared | withdrawal_initiated | withdrawal_completed | withdrawal_cancelled | order_placed(this is to get used_for_purchase)
            $table->unsignedBigInteger('amount')->nullable();
            $table->dateTime('log_created_at')->nullable();
            $table->dateTime('order_earning_clearnace_date')->nullable();
            
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
        Schema::dropIfExists('user_transaction_logs');
    }
}
