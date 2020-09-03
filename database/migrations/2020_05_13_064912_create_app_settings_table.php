<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('auto_review_requests')->default(true)->nullable();
            $table->boolean('auto_review_gigs')->default(true)->nullable();
            $table->unsignedBigInteger('auto_mark_order_completed_time')->default(3)->nullable();
            $table->unsignedBigInteger('order_commission_from_buyer')->default(2)->nullable(); // this is exact amount to add while buyer place order // 2$
            $table->unsignedBigInteger('order_commission_from_seller')->default(20)->nullable(); // this is percentage amount to cut when giving order price to seller // 20%
            $table->unsignedBigInteger('minimum_withdrawable_amount_for_paypalWithdraw')->default(50)->nullable(); // this is exact amount that a seller need to have in order to request a withdraw // 50$
            $table->unsignedBigInteger('minimum_withdrawable_amount_for_payoneerWithdraw')->default(100)->nullable(); // this is exact amount that a seller need to have in order to request a withdraw // 100$
            $table->unsignedBigInteger('minimum_withdrawable_amount_for_manualWithdraw')->default(100)->nullable(); // this is exact amount that a seller need to have in order to request a withdraw // 100$
            $table->unsignedBigInteger('seller_amount_pending_clearnace_time')->default(10)->nullable(); // enter number of days required for amount to clear for withdraw after order is marked as completed
            $table->string("paypal_payout_api_user")->default("AWEPVBZen2HJ5mrRxf__LOG6UI2xSOXK5Z4t1LNr0f5NDzYAT-NTrx9fJ7QTGD8AK9XZIP2Uq4pFHn-p")->nullable();
            $table->string("paypal_payout_api_password")->default("EI_JQdSrtUln5Tc050mr6Z3NvNu65uEZLwSdV-pnC-GGUku91_1SV7SrerCJFOHSF95CZhA9xVEmcC0B")->nullable();
            $table->longText("gig_category_listing_page_filters")->nullable();
            $table->longText("home_map_numbers_increment")->nullable();
            $table->longText("home_review_section_video")->nullable();
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
        Schema::dropIfExists('app_settings');
    }
}
