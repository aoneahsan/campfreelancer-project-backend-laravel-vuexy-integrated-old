<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('phone_number')->unique()->nullable();
            $table->string('country_code')->nullable();
            $table->string('country_code_text')->nullable();
            $table->string('authy_id')->nullable();
            $table->text('profile_img')->nullable();
            $table->text('role')->nullable();
            $table->boolean('is_buyer')->default(false)->nullable();
            $table->boolean('is_2fa_verified')->default(false)->nullable();
            $table->boolean('is_2fa_enabled')->default(false)->nullable();
            $table->unsignedBigInteger('membership_plan_id')->default(1)->nullable();
            $table->string('profile_publicly_visible')->default('visible')->nullable();
            $table->softDeletes();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
