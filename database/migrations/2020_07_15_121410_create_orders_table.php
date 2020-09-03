<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('buyer_id')->nullable();
            $table->unsignedBigInteger('seller_id')->nullable();
            $table->unsignedBigInteger('gig_id')->nullable();
            $table->string('status')->nullable();
            $table->string('order_number')->nullable(); // call it order_id or whatever a unique order identifier to use in frontend (if you don't want to show order id there)
            $table->string('order_source')->nullable();  // custom_offer | gig to specify the tag
            $table->string('order_title')->nullable();
            $table->text('order_description')->nullable();
            $table->dateTime('order_time')->nullable();
            $table->string('order_time_in_days')->nullable();
            $table->unsignedBigInteger('price')->nullable();
            $table->unsignedBigInteger('seller_earning')->nullable();
            $table->string('revisions')->nullable();
            $table->dateTime('order_rated_at')->nullable();
            $table->dateTime('buyer_feedback_at')->nullable();
            $table->dateTime('seller_feedback_at')->nullable();
            $table->dateTime('buyer_placed_tip_at')->nullable();
            $table->boolean('ask_for_requirements')->nullable();
            $table->dateTime('requirements_submited_at')->nullable();
            $table->text('order_requirement_title')->nullable();
            $table->text('order_requirement_description')->nullable();
            $table->boolean('is_favorite')->default(false)->nullable();
            $table->boolean('is_late')->default(false)->nullable(); // determine if order is behind its delivery date
            $table->dateTime('order_delivery_date')->nullable(); // use this to manage auto complete after three days, how?, simply when seller deliver order put date in this field and schdule a task to check this date every min,(every min because there will be too many orders), and when this delivery date become more than 3 days mark order status completed, below 3 days mark as delivery and if buyer ask for revisions mark as in revision
            $table->dateTime('order_delivered_at')->nullable();
            $table->dateTime('order_cancelled_at')->nullable();
            $table->string('order_cancel_reason')->nullable();
            $table->boolean('is_cleared')->default(false)->nullable(); // this is to specify is order payment is cleared for seller to withdraw (if its 'true' than user is able to withdraw payment (use together with 'withdrawn_at' column to specify if user has already withdrawn the payment))
            $table->dateTime('order_completed_at')->nullable(); // on order completed put new date in it and than use this column value to determine pending_clearnace time
            $table->dateTime('amount_will_clear_at')->nullable(); // add "day require for amount clearnace" in "order_completed_at" column field and put in this field simply use this to calculate pending_clearnace time.
            $table->dateTime('amount_cleared_at')->nullable(); // this is to remove amount from pending_clearance and put it in available_for_withdrawal.
            $table->dateTime('amount_added_in_seller_account_at')->nullable(); // "null" mean have not added withdrawn request yet, othervise has placed withdrawn request waiting for approval
            // $table->dateTime('withdrawn_request_at')->nullable(); // "null" mean have not added withdrawn request yet, othervise has placed withdrawn request waiting for approval
            // $table->dateTime('withdrawn_at')->nullable(); // if its "null" mean user have not withdrawn yet, othervise user has
            $table->longText('checkout_response')->nullable();

            // $table->foreign('buyer_id')->references('id')->on('users')->onDelete('cascade');
            
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
        Schema::dropIfExists('orders');
    }
}
