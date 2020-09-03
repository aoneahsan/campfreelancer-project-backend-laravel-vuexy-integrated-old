<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembershipPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('membership_plans', function (Blueprint $table) {
            $table->id();
            $table->string('plan_number')->nullable();
            $table->text('title')->nullable();
            $table->longText('description')->nullable();
            $table->unsignedBigInteger('price')->default(0)->nullable();
            $table->boolean('can_offer_requests')->default(false)->nullable();
            $table->unsignedBigInteger('bids_allowed')->default(10)->nullable();
            $table->unsignedBigInteger('order_placing_service_charges')->default(7)->nullable();
            $table->unsignedBigInteger('commission_per_order')->default(20)->nullable();
            $table->boolean('can_post_request')->default(false)->nullable();
            $table->boolean('post_premium_requests')->default(false)->nullable();
            $table->boolean('show_primium_request')->default(false)->nullable();
            $table->boolean('can_add_gigs')->default(false)->nullable();
            $table->string('plan_type')->nullable();
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
        Schema::dropIfExists('membership_plans');
    }
}
