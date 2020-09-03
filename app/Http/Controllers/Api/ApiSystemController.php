<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

use App\User;
use App\Model\User\UserAccount;
use App\Model\User\UserDetails;

use App\Model\App\AppSetting;
use App\Model\Gig\GigPackage;
use App\Model\Gig\GigRequirement;
use App\Model\Gig\UserGig;
use App\Model\Order\OrderFeedback;
use App\Model\Shared\MembershipPlan;
use App\Model\User\UserTransactionLog;
use Carbon\Carbon;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Illuminate\Support\Str;

class ApiSystemController extends Controller
{
    public function InitAppDefaultUsersSetup()
    {
        // Setup App Settings (auto review request/gigs etc)
        AppSetting::create([
            'auto_review_requests' => true,
            'auto_review_gigs' => true
        ]);

        // Add Basic Membership Plans
        // Free User Plan
        MembershipPlan::create([
            'plan_number' => uniqid(),
            'title' => 'Free Plan',
            'description' => 'free plan for users.',
            'price' => 0,
            'can_offer_requests' => true,
            'bids_allowed' => 3,
            'commission_per_order' => 30,
            'order_placing_service_charges' => 7,
            'can_post_request' => true,
            'post_premium_requests' => false,
            'show_primium_request' => false,
            'can_add_gigs' => true,
            'plan_type' => 'free'
        ]);
        // Basic User Plan
        MembershipPlan::create([
            'plan_number' => uniqid(),
            'title' => 'Basic Plan',
            'description' => 'basic plan for users.',
            'price' => 20, // $
            'can_offer_requests' => true,
            'bids_allowed' => 10,
            'commission_per_order' => 20,
            'order_placing_service_charges' => 5,
            'can_post_request' => true,
            'post_premium_requests' => false,
            'show_primium_request' => false,
            'can_add_gigs' => true,
            'plan_type' => 'basic'
        ]);
        // Premium User Plan
        MembershipPlan::create([
            'plan_number' => uniqid(),
            'title' => 'Premium Plan',
            'description' => 'Premium plan for users.',
            'price' => 59, // $
            'can_offer_requests' => true,
            'bids_allowed' => 15,
            'commission_per_order' => 10,
            'order_placing_service_charges' => 3,
            'can_post_request' => true,
            'post_premium_requests' => true,
            'show_primium_request' => true,
            'can_add_gigs' => true,
            'plan_type' => 'premium'
        ]);

        // Creating Admin
        $admin = User::create([
            'username' => 'admin',
            'name' => 'admin',
            'email' => 'admin@demo.com',
            'phone_number' => '03000000001',
            'password' => Hash::make('123456'),
            'role' => 'admin',
            'is_buyer' => 0,
            'membership_plan_id' => 1
        ]);
        UserDetails::create([
            'user_id' => $admin->id
        ]);
        UserAccount::create([
            'user_id' => $admin->id
        ]);

        // Creating buyer
        $buyer = User::create([
            'username' => 'buyer',
            'name' => 'buyer',
            'email' => 'buyer@demo.com',
            'phone_number' => '03000000002',
            'password' => Hash::make('123456'),
            'role' => 'buyer',
            'is_buyer' => 1,
            'membership_plan_id' => 1
        ]);
        UserDetails::create([
            'user_id' => $buyer->id
        ]);
        UserAccount::create([
            'user_id' => $buyer->id
        ]);

        // Creating seller
        $seller = User::create([
            'username' => 'seller',
            'name' => 'seller',
            'email' => 'seller@demo.com',
            'phone_number' => '03000000003',
            'password' => Hash::make('123456'),
            'role' => 'seller',
            'is_buyer' => 0,
            'membership_plan_id' => 1
        ]);
        UserDetails::create([
            'user_id' => $seller->id
        ]);
        UserAccount::create([
            'user_id' => $seller->id
        ]);

        $ahsan1 = User::create([
            'username' => 'ahsan1',
            'name' => 'ahsan1',
            'email' => 'student@pnyexam.com',
            'phone_number' => '03000000004',
            'password' => Hash::make('123456'),
            'role' => 'buyer',
            'is_buyer' => 1,
            'membership_plan_id' => 1
        ]);
        UserDetails::create([
            'user_id' => $ahsan1->id
        ]);
        UserAccount::create([
            'user_id' => $ahsan1->id,
            'balance' => 100
        ]);

        $ahsan2 = User::create([
            'username' => 'ahsan2',
            'name' => 'ahsan2',
            'email' => 'student@pnyexam.com1',
            'phone_number' => '03000000005',
            'password' => Hash::make('123456'),
            'role' => 'seller',
            'is_buyer' => 0,
            'membership_plan_id' => 1
        ]);
        UserDetails::create([
            'user_id' => $ahsan2->id
        ]);
        UserAccount::create([
            'user_id' => $ahsan2->id,
            'balance' => 1000
        ]);

        $ahsan3 = User::create([
            'username' => 'ahsan3',
            'name' => 'ahsan3',
            'email' => 'student@pnyexam.com2',
            'phone_number' => '03000000006',
            'password' => Hash::make('123456'),
            'role' => 'seller',
            'is_buyer' => 0,
            'membership_plan_id' => 1
        ]);
        UserDetails::create([
            'user_id' => $ahsan3->id
        ]);
        UserAccount::create([
            'user_id' => $ahsan3->id,
            'balance' => 10
        ]);

        // Roles
        $admin_role = Role::create(['name' => 'admin']);
        $buyer_role = Role::create(['name' => 'buyer']);
        $seller_role = Role::create(['name' => 'seller']);

        // Permissions
        $app_user_p = Permission::create(['name' => 'app_user']);
        $view_dashboard_p = Permission::create(['name' => 'view_dashboard']);
        $add_new_user_p = Permission::create(['name' => 'add_new_user']);

        // Giving Permissions to Roles
        // Admin Role
        $admin_role->givePermissionTo($app_user_p);
        $admin_role->givePermissionTo($view_dashboard_p);
        $admin_role->givePermissionTo($add_new_user_p);

        // Buyer Role
        $buyer_role->givePermissionTo($app_user_p);
        $buyer_role->givePermissionTo($view_dashboard_p);

        // Seller Role
        $seller_role->givePermissionTo($app_user_p);
        $seller_role->givePermissionTo($view_dashboard_p);

        // Assigning Roles To Users
        $admin->assignRole($admin_role);
        $buyer->assignRole($buyer_role);
        $seller->assignRole($seller_role);
        $ahsan1->assignRole($seller_role);
        $ahsan2->assignRole($buyer_role);
        $ahsan3->assignRole($seller_role);

        $gigTitle = "Test Gig Test Gig Test Gig Test Gig Test Gig";
        $gigSlug = Str::slug($gigTitle);

        // ahsan1 gig
        $gig1 = UserGig::create([
            'user_id' => $ahsan1->id,
            'category_id' => 1,
            'subcategory_id' => 5,
            'service_type_id' => 1,
            'title' => $gigTitle,
            'slug' => $gigSlug,
            'description' => "Test Gig Des",
            'status' => 'publish',
            'gig_type' => null,
            'tags' => json_encode(['ahsan', 'gig']),
            // 'hourly_rate' => $request->has('hourly_rate') ? $request->hourly_rate : null,
            'is_three_packages_mode_on' => false,
            'is_extra_fast_delivery_on' => false,
            'is_home_map_feature_item' => true,
            'is_home_expert_section_item' => true
        ]);
        GigPackage::create([
            'gig_id' => $gig1->id,
            'title' => "basic",
            'description' => "basic package",
            'price' => 132,
            'time' => 6,
            'revisions' => 7,
            'price' => 9
        ]);
        GigRequirement::create([
            'gig_id' => $gig1->id,
            'title' => "okasdasd",
            'description' => "asfjsahkadsfkjsdf sdafsadf saf sdf sfs afasdf",
            'is_required' => true
        ]);

        $orderFeedbacks = [
            ['buyer_id' => 5, 'seller_id' => 4, 'order_id' => 1, 'gig_id' => 1, 'buyer_feedback_at' => Carbon::now(), 'buyer_feedback' => 'Over 800,562 people and businesses have come to us for their custom logos, websites, books and all types of graphic design. Read their reviews of CampFreelancer to learn how great work changed their business.', 'buyer_satisfaction_level' => 5, 'buyer_rating_sellerCommunication' => 5, 'buyer_rating_serviceAsDescribed' => 5, 'buyer_rating_sellerRecommended' => 5, 'buyer_rating' => 5, 'seller_feedback_at' => Carbon::now(), 'seller_feedback' => 5, 'seller_rating_buyerCommunication' => 5, 'seller_rating_buyerRecommended' => 5, 'seller_rating' => 5, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['buyer_id' => 5, 'seller_id' => 4, 'order_id' => 1, 'gig_id' => 1, 'buyer_feedback_at' => Carbon::now(), 'buyer_feedback' => 'Over 800,562 people and businesses have come to us for their custom logos, websites, books and all types of graphic design. Read their reviews of CampFreelancer to learn how great work changed their business.', 'buyer_satisfaction_level' => 5, 'buyer_rating_sellerCommunication' => 5, 'buyer_rating_serviceAsDescribed' => 5, 'buyer_rating_sellerRecommended' => 5, 'buyer_rating' => 5, 'seller_feedback_at' => Carbon::now(), 'seller_feedback' => 5, 'seller_rating_buyerCommunication' => 5, 'seller_rating_buyerRecommended' => 5, 'seller_rating' => 5, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['buyer_id' => 5, 'seller_id' => 4, 'order_id' => 1, 'gig_id' => 1, 'buyer_feedback_at' => Carbon::now(), 'buyer_feedback' => 'Over 800,562 people and businesses have come to us for their custom logos, websites, books and all types of graphic design. Read their reviews of CampFreelancer to learn how great work changed their business.', 'buyer_satisfaction_level' => 5, 'buyer_rating_sellerCommunication' => 5, 'buyer_rating_serviceAsDescribed' => 5, 'buyer_rating_sellerRecommended' => 5, 'buyer_rating' => 5, 'seller_feedback_at' => Carbon::now(), 'seller_feedback' => 5, 'seller_rating_buyerCommunication' => 5, 'seller_rating_buyerRecommended' => 5, 'seller_rating' => 5, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['buyer_id' => 5, 'seller_id' => 4, 'order_id' => 1, 'gig_id' => 1, 'buyer_feedback_at' => Carbon::now(), 'buyer_feedback' => 'Over 800,562 people and businesses have come to us for their custom logos, websites, books and all types of graphic design. Read their reviews of CampFreelancer to learn how great work changed their business.', 'buyer_satisfaction_level' => 5, 'buyer_rating_sellerCommunication' => 5, 'buyer_rating_serviceAsDescribed' => 5, 'buyer_rating_sellerRecommended' => 5, 'buyer_rating' => 5, 'seller_feedback_at' => Carbon::now(), 'seller_feedback' => 5, 'seller_rating_buyerCommunication' => 5, 'seller_rating_buyerRecommended' => 5, 'seller_rating' => 5, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['buyer_id' => 5, 'seller_id' => 4, 'order_id' => 1, 'gig_id' => 1, 'buyer_feedback_at' => Carbon::now(), 'buyer_feedback' => 'Over 800,562 people and businesses have come to us for their custom logos, websites, books and all types of graphic design. Read their reviews of CampFreelancer to learn how great work changed their business.', 'buyer_satisfaction_level' => 5, 'buyer_rating_sellerCommunication' => 5, 'buyer_rating_serviceAsDescribed' => 5, 'buyer_rating_sellerRecommended' => 5, 'buyer_rating' => 5, 'seller_feedback_at' => Carbon::now(), 'seller_feedback' => 5, 'seller_rating_buyerCommunication' => 5, 'seller_rating_buyerRecommended' => 5, 'seller_rating' => 5, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['buyer_id' => 5, 'seller_id' => 4, 'order_id' => 1, 'gig_id' => 1, 'buyer_feedback_at' => Carbon::now(), 'buyer_feedback' => 'Over 800,562 people and businesses have come to us for their custom logos, websites, books and all types of graphic design. Read their reviews of CampFreelancer to learn how great work changed their business.', 'buyer_satisfaction_level' => 5, 'buyer_rating_sellerCommunication' => 5, 'buyer_rating_serviceAsDescribed' => 5, 'buyer_rating_sellerRecommended' => 5, 'buyer_rating' => 5, 'seller_feedback_at' => Carbon::now(), 'seller_feedback' => 5, 'seller_rating_buyerCommunication' => 5, 'seller_rating_buyerRecommended' => 5, 'seller_rating' => 5, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['buyer_id' => 5, 'seller_id' => 4, 'order_id' => 1, 'gig_id' => 1, 'buyer_feedback_at' => Carbon::now(), 'buyer_feedback' => 'Over 800,562 people and businesses have come to us for their custom logos, websites, books and all types of graphic design. Read their reviews of CampFreelancer to learn how great work changed their business.', 'buyer_satisfaction_level' => 5, 'buyer_rating_sellerCommunication' => 5, 'buyer_rating_serviceAsDescribed' => 5, 'buyer_rating_sellerRecommended' => 5, 'buyer_rating' => 5, 'seller_feedback_at' => Carbon::now(), 'seller_feedback' => 5, 'seller_rating_buyerCommunication' => 5, 'seller_rating_buyerRecommended' => 5, 'seller_rating' => 5, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['buyer_id' => 5, 'seller_id' => 4, 'order_id' => 1, 'gig_id' => 1, 'buyer_feedback_at' => Carbon::now(), 'buyer_feedback' => 'Over 800,562 people and businesses have come to us for their custom logos, websites, books and all types of graphic design. Read their reviews of CampFreelancer to learn how great work changed their business.', 'buyer_satisfaction_level' => 5, 'buyer_rating_sellerCommunication' => 5, 'buyer_rating_serviceAsDescribed' => 5, 'buyer_rating_sellerRecommended' => 5, 'buyer_rating' => 5, 'seller_feedback_at' => Carbon::now(), 'seller_feedback' => 5, 'seller_rating_buyerCommunication' => 5, 'seller_rating_buyerRecommended' => 5, 'seller_rating' => 5, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]
        ];
        OrderFeedback::insert($orderFeedbacks);

        $userTransactionalLogs = [
            ['user_id' => 4, 'order_id' => 1, "order_number" => "asdadsfasdasd", 'transaction_log_type' => 'order_revenue', 'amount' => 40, 'log_created_at' => Carbon::now(), 'order_earning_clearnace_date' => Carbon::now()],
            ['user_id' => 4, 'order_id' => 1, "order_number" => "asdadsfasdasd", 'transaction_log_type' => 'order_revenue', 'amount' => 40, 'log_created_at' => Carbon::now(), 'order_earning_clearnace_date' => Carbon::now()],
            ['user_id' => 4, 'order_id' => 1, "order_number" => "asdadsfasdasd", 'transaction_log_type' => 'order_revenue', 'amount' => 40, 'log_created_at' => Carbon::now(), 'order_earning_clearnace_date' => Carbon::now()],
            ['user_id' => 4, 'order_id' => 1, "order_number" => "asdadsfasdasd", 'transaction_log_type' => 'funds_cleared', 'amount' => 40, 'log_created_at' => Carbon::now(), 'order_earning_clearnace_date' => Carbon::now()],
            ['user_id' => 4, 'order_id' => 1, "order_number" => "asdadsfasdasd", 'transaction_log_type' => 'order_revenue', 'amount' => 40, 'log_created_at' => Carbon::now(), 'order_earning_clearnace_date' => Carbon::now()],
            ['user_id' => 4, 'order_id' => 1, "order_number" => "asdadsfasdasd", 'transaction_log_type' => 'funds_cleared', 'amount' => 40, 'log_created_at' => Carbon::now(), 'order_earning_clearnace_date' => Carbon::now()],
            ['user_id' => 4, 'order_id' => 1, "order_number" => "asdadsfasdasd", 'transaction_log_type' => 'withdrawal_initiated', 'amount' => 40, 'log_created_at' => Carbon::now(), 'order_earning_clearnace_date' => Carbon::now()],
            ['user_id' => 4, 'order_id' => 1, "order_number" => "asdadsfasdasd", 'transaction_log_type' => 'withdrawal_completed', 'amount' => 40, 'log_created_at' => Carbon::now(), 'order_earning_clearnace_date' => Carbon::now()],
            ['user_id' => 4, 'order_id' => 1, "order_number" => "asdadsfasdasd", 'transaction_log_type' => 'withdrawal_cancelled', 'amount' => 40, 'log_created_at' => Carbon::now(), 'order_earning_clearnace_date' => Carbon::now()],
            ['user_id' => 4, 'order_id' => 1, "order_number" => "asdadsfasdasd", 'transaction_log_type' => 'order_revenue', 'amount' => 40, 'log_created_at' => Carbon::now(), 'order_earning_clearnace_date' => Carbon::now()],
            ['user_id' => 4, 'order_id' => 1, "order_number" => "asdadsfasdasd", 'transaction_log_type' => 'order_placed', 'amount' => 40, 'log_created_at' => Carbon::now(), 'order_earning_clearnace_date' => Carbon::now()],
            ['user_id' => 4, 'order_id' => 1, "order_number" => "asdadsfasdasd", 'transaction_log_type' => 'order_placed', 'amount' => 40, 'log_created_at' => Carbon::now(), 'order_earning_clearnace_date' => Carbon::now()],
            ['user_id' => 4, 'order_id' => 1, "order_number" => "asdadsfasdasd", 'transaction_log_type' => 'order_placed', 'amount' => 40, 'log_created_at' => Carbon::now(), 'order_earning_clearnace_date' => Carbon::now()]
        ];

        UserTransactionLog::insert($userTransactionalLogs);

        // ahsan2 gig
        $gig = UserGig::create([
            'user_id' => $ahsan2->id,
            'category_id' => 1,
            'subcategory_id' => 5,
            'service_type_id' => 1,
            'title' => $gigTitle,
            'slug' => $gigSlug,
            'description' => "Test Gig Des",
            'status' => 'publish',
            'gig_type' => null,
            'tags' => json_encode(['ahsan', 'gig']),
            // 'hourly_rate' => $request->has('hourly_rate') ? $request->hourly_rate : null,
            'is_three_packages_mode_on' => false,
            'is_extra_fast_delivery_on' => false,
            'is_home_map_feature_item' => true,
            'is_home_expert_section_item' => true
        ]);
        GigPackage::create([
            'gig_id' => $gig->id,
            'title' => "basic",
            'description' => "basic package",
            'price' => 132,
            'time' => 6,
            'revisions' => 7,
            'price' => 13
        ]);
        GigRequirement::create([
            'gig_id' => $gig->id,
            'title' => "okasdasd",
            'description' => "asfjsahkadsfkjsdf sdafsadf saf sdf sfs afasdf",
            'is_required' => true
        ]);

        // ahsan3 gig
        $gig = UserGig::create([
            'user_id' => $ahsan3->id,
            'category_id' => 1,
            'subcategory_id' => 5,
            'service_type_id' => 1,
            'title' => $gigTitle,
            'slug' => $gigSlug,
            'description' => "Test Gig Des",
            'status' => 'publish',
            'gig_type' => null,
            'tags' => json_encode(['ahsan', 'gig']),
            // 'hourly_rate' => $request->has('hourly_rate') ? $request->hourly_rate : null,
            'is_three_packages_mode_on' => false,
            'is_extra_fast_delivery_on' => false,
            'is_home_map_feature_item' => true,
            'is_home_expert_section_item' => true
        ]);
        GigPackage::create([
            'gig_id' => $gig->id,
            'title' => "basic",
            'description' => "basic package",
            'price' => 132,
            'time' => 6,
            'revisions' => 7,
            'price' => 19
        ]);
        GigRequirement::create([
            'gig_id' => $gig->id,
            'title' => "okasdasd",
            'description' => "asfjsahkadsfkjsdf sdafsadf saf sdf sfs afasdf",
            'is_required' => true
        ]);

        // buyer gig
        $gig = UserGig::create([
            'user_id' => $buyer->id,
            'category_id' => 1,
            'subcategory_id' => 5,
            'service_type_id' => 1,
            'title' => $gigTitle,
            'slug' => $gigSlug,
            'description' => "Test Gig Des",
            'status' => 'publish',
            'gig_type' => null,
            'tags' => json_encode(['ahsan', 'gig']),
            // 'hourly_rate' => $request->has('hourly_rate') ? $request->hourly_rate : null,
            'is_three_packages_mode_on' => false,
            'is_extra_fast_delivery_on' => false,
            'is_home_map_feature_item' => true,
            'is_home_expert_section_item' => true
        ]);
        GigPackage::create([
            'gig_id' => $gig->id,
            'title' => "basic",
            'description' => "basic package",
            'price' => 132,
            'time' => 6,
            'revisions' => 7,
            'price' => 11
        ]);
        GigRequirement::create([
            'gig_id' => $gig->id,
            'title' => "okasdasd",
            'description' => "asfjsahkadsfkjsdf sdafsadf saf sdf sfs afasdf",
            'is_required' => true
        ]);

        // seller gig
        $gig = UserGig::create([
            'user_id' => $seller->id,
            'category_id' => 1,
            'subcategory_id' => 5,
            'service_type_id' => 1,
            'title' => $gigTitle,
            'slug' => $gigSlug,
            'description' => "Test Gig Des",
            'status' => 'publish',
            'gig_type' => null,
            'tags' => json_encode(['ahsan', 'gig']),
            // 'hourly_rate' => $request->has('hourly_rate') ? $request->hourly_rate : null,
            'is_three_packages_mode_on' => false,
            'is_extra_fast_delivery_on' => false,
            'is_home_map_feature_item' => true,
            'is_home_expert_section_item' => true
        ]);
        GigPackage::create([
            'gig_id' => $gig->id,
            'title' => "basic",
            'description' => "basic package",
            'price' => 132,
            'time' => 6,
            'revisions' => 7,
            'price' => 7
        ]);
        GigRequirement::create([
            'gig_id' => $gig->id,
            'title' => "okasdasd",
            'description' => "asfjsahkadsfkjsdf sdafsadf saf sdf sfs afasdf",
            'is_required' => true
        ]);

        // ahsan1 gig
        $gig = UserGig::create([
            'user_id' => $ahsan1->id,
            'category_id' => 1,
            'subcategory_id' => 5,
            'service_type_id' => 1,
            'title' => $gigTitle,
            'slug' => $gigSlug,
            'description' => "Test Gig Des",
            'status' => 'publish',
            'gig_type' => null,
            'tags' => json_encode(['ahsan', 'gig']),
            // 'hourly_rate' => $request->has('hourly_rate') ? $request->hourly_rate : null,
            'is_three_packages_mode_on' => false,
            'is_extra_fast_delivery_on' => false,
            'is_home_map_feature_item' => true,
            'is_home_expert_section_item' => true
        ]);
        GigPackage::create([
            'gig_id' => $gig->id,
            'title' => "basic",
            'description' => "basic package",
            'price' => 132,
            'time' => 6,
            'revisions' => 7,
            'price' => 12
        ]);
        GigRequirement::create([
            'gig_id' => $gig->id,
            'title' => "okasdasd",
            'description' => "asfjsahkadsfkjsdf sdafsadf saf sdf sfs afasdf",
            'is_required' => true
        ]);

        // Init Gig listing page available filters Options
        $gigListingPageFilteroptions = [
            "current_pagination_page" => 1,
            "pagination_items_per_page" => 3,
            "query" => true,
            "parent_category" => true,
            "location" => true,
            "min_price" => true,
            "max_price" => true,
            "delivery_time" => true,
            "gig_rating" => true,
            "gig_level" => true
        ];
        $homeMapSectionIncrements = [
            "total_freelancers" => 42381,
            "total_withdrawn_amount" => 981293,
            "total_hours_tacked" => 87123
        ];
        $home_review_section_video = [
            'image_file_path' => 'custom-assets/img/defaultPlaceholder.png',
            'video_file_path' => null
        ];
        AppSetting::first()->update([
            'gig_category_listing_page_filters' => json_encode($gigListingPageFilteroptions),
            'home_map_numbers_increment' => json_encode($homeMapSectionIncrements),
            'home_review_section_video' => json_encode($home_review_section_video)
        ]);

        return "All Done Users Created and Roles Assigned With respective Permissions";
    }
}
