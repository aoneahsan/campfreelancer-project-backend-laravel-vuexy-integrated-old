<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('balance')->default(0);
            // $table->unsignedBigInteger('net_income')->default(0);
            // $table->unsignedBigInteger('withdrawn')->default(0);
            // $table->unsignedBigInteger('used_for_purhases')->default(0);
            // $table->unsignedBigInteger('pending_clearance')->default(0);
            // $table->unsignedBigInteger('available_for_withdrawal')->default(0);
            $table->boolean('accept_custom_offers')->default(true); // this is on gigs listing page the switch to accept custom offers 
            
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
        Schema::dropIfExists('user_accounts');
    }
}
