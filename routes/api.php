<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get("okok", function () {
    return "working";
});

Route::post('login', 'Api\Auth\ApiAuthController@loginApi');
Route::post('register', 'Api\Auth\ApiAuthController@registerApi');

// ********************************************************************************
// ***************************   Admin Panel APIs    ****************************
// ********************************************************************************

Route::group([
    'middleware' => 'auth:sanctum',
    'namespace' => 'Api'
], function () {
    // Gig Categories APIs
    Route::get('gig/categories', 'Gig\ApiGigCategoryController@index');
    Route::post('gig/categories', 'Gig\ApiGigCategoryController@store');
    Route::get('gig/categories/{id}', 'Gig\ApiGigCategoryController@show');
    Route::post('gig/categories/{id}/update', 'Gig\ApiGigCategoryController@update');
    Route::delete('gig/categories/{id}/delete', 'Gig\ApiGigCategoryController@destroy');

    // Gig ServiceType APIs
    Route::get('gig/servicetypes', 'Gig\ApiGigServiceTypeController@index');
    Route::post('gig/servicetypes', 'Gig\ApiGigServiceTypeController@store');
    Route::get('gig/servicetypes/{id}', 'Gig\ApiGigServiceTypeController@show');
    Route::post('gig/servicetypes/{id}/update', 'Gig\ApiGigServiceTypeController@update');
    Route::delete('gig/servicetypes/{id}/delete', 'Gig\ApiGigServiceTypeController@destroy');
});

// ********************************************************************************
// ***************************  App User APIs  ************************************
// ********************************************************************************
Route::group([
    'middleware' => 'auth:sanctum',
    'namespace' => 'Api'
], function () {
    // Verify User Account
    Route::get('verify-account', 'Auth\ApiAuthController@startTwoFactorAuth'); // this will make user authy account and send a verification code at user phone number
    Route::post('verify-account', 'Auth\ApiAuthController@verifyAccount');
    Route::get('resend-verification-code', 'Auth\ApiAuthController@resendVerificationCode')->middleware('throttle:1,1');

    // Social Login Handle
    Route::get('user/social-login', 'Auth\ApiAuthController@socialLoginHandle');

    // Check Login Status To AutoLogout
    Route::get('check-login-status', 'Auth\ApiAuthController@checkLoginStatus');

    // Change User Password
    Route::post('user/change-password', 'Auth\ApiAuthController@changePassword');

    // Update User Role
    Route::post('user-role/update', 'User\ApiUserController@changeUserRole');

    // Logout API
    Route::post('logout', 'Auth\ApiAuthController@logoutApi');

    // User Profile APIs
    Route::get('user/profile', 'User\ApiUserController@getUserProfileData');
    Route::post('user/profile/update', 'User\ApiUserController@updateUserProfile');
    Route::post('user/profile-img/update', 'User\ApiUserController@updateUserProfileImage');
    Route::delete('user', 'User\ApiUserController@deleteUserAccount');

    // Search User API
    Route::post('search/user', 'User\ApiUserController@searchPerson');

    // User Security Questions APIs
    Route::get('user/security-questions', 'User\ApiUserController@getUserSecurityQuestions');
    // Route::post('user/security-questions/add-single', 'User\ApiUserController@addUserSecurityQuestion'); // add single question
    Route::post('user/security-questions/add', 'User\ApiUserController@addUserSecurityQuestions'); // add all questions (3)
    Route::post('user/security-questions/update', 'User\ApiUserController@updateUserSecurityQuestions'); // update all question (3)
    Route::post('user/security-questions/{id}/update', 'User\ApiUserController@updateUserSecurityQuestion');
    Route::delete('user/security-questions/{id}/delete', 'User\ApiUserController@deleteUserSecurityQuestion');

    // User Payment Methods  (just store, update, delete) (because userprofile contains all payment methods so no need to fetch)
    Route::post('user/payment-methods', 'User\ApiUserPaymentMethodController@store'); // add all questions (3)
    Route::post('user/payment-methods/{id}/update', 'User\ApiUserPaymentMethodController@update');
    Route::delete('user/payment-methods/{id}/delete', 'User\ApiUserPaymentMethodController@destroy');

    // User Earnings Related Routes
    Route::get('user/earnings', 'User\ApiUserEarningsController@index');
    Route::get('user/transactional-logs/{days?}', 'User\ApiUserEarningsController@getTransactionalLogs');

    // User Analytics Routes APIs
    Route::get('user/analytics-report', 'User\ApiUserAnalyticsController@index');
    Route::get('user/transactional-logs/{days?}', 'User\ApiUserAnalyticsController@getTransactionalLogs');

    // User Payout Requests Routes APIs
    Route::post('payout/check-balance-withdrawamount', 'Payout\ApiPayoutController@checkBalanceAndWithdrawAmount');
    Route::get('payout/place-request', 'Payout\ApiPayoutController@store');

    // Personal Gigs APIs
    Route::get('user/gigs/{days_for_stats?}', 'Gig\ApiUserGigController@index'); // sort gigs listing page
    Route::get('user/gigs/status/{status?}', 'Gig\ApiUserGigController@getSpecificStatusGigs'); // get gigs of specific status // default is publish
    Route::post('user/gigs', 'Gig\ApiUserGigController@store');
    Route::get('user/gigs/{id}/data', 'Gig\ApiUserGigController@show'); // this is used in edit gig and other
    Route::get('user/gigs/{id}/preview', 'Gig\ApiUserGigController@getPreviewGigData'); // this is used in preview gig
    Route::put('user/gigs/{id}/{status}', 'Gig\ApiUserGigController@changeGigStatus'); // change gig status
    Route::post('user/gigs/{id}/update', 'Gig\ApiUserGigController@update');
    Route::delete('user/gigs/{id}/delete', 'Gig\ApiUserGigController@destroy');
    Route::delete('user/gigs/{status}', 'Gig\ApiUserGigController@deleteGigsWithStatus'); // delete all gigs wih specific status

    // Gig Categories
    Route::get('gig-categories/parent', 'Gig\ApiGigCategoryController@getParentCategories');
    Route::get('gig-categories/{parentId}/childs', 'Gig\ApiGigCategoryController@getChildCategories');

    // Gig ServiceType
    Route::get('gig-categories/{childID}/service-types', 'Gig\ApiGigCategoryController@getCategoryServiceTypes');

    // Personal JobRequests APIs
    Route::get('user/jobrequests', 'JobRequest\ApiJobRequestController@index');
    Route::get('user/available-jobrequests', 'JobRequest\ApiJobRequestController@getAvailableJobRequests');
    Route::post('user/jobrequests', 'JobRequest\ApiJobRequestController@store');
    Route::get('user/jobrequests/{id}/datatoedit', 'JobRequest\ApiJobRequestController@getJobRequestDataToEdit');
    Route::get('user/jobrequests/{id}/data', 'JobRequest\ApiJobRequestController@show');
    Route::put('user/jobrequests/{id}/{status}', 'JobRequest\ApiJobRequestController@changeJobRequestStatus'); // change gig status
    Route::post('user/jobrequests/{id}/update', 'JobRequest\ApiJobRequestController@update');
    Route::delete('user/jobrequests/{id}/delete', 'JobRequest\ApiJobRequestController@destroy');
    Route::delete('user/jobrequests/{status}', 'JobRequest\ApiJobRequestController@deleteJobRequestsWithStatus'); // delete all gigs wih specific status
    Route::get('username/{id}', 'JobRequest\ApiJobRequestController@getGigDetails'); // this is to show JobRequest publicaly

    // Seller Routes For JobRequests APIs
    Route::get('jobrequests', 'JobRequest\ApiJobRequestController@getJobRequests');
    Route::get('user/gig-categories', 'JobRequest\ApiJobRequestController@userGigCategories'); // to get seller gig categories to apply filter

    // Membership Plans APIs
    Route::get('membership-plans', 'Shared\ApiMembershipPlanController@index');
    Route::post('membership-plans', 'Shared\ApiMembershipPlanController@store');
    Route::get('membership-plans/{plan_number}', 'Shared\ApiMembershipPlanController@show');
    Route::post('membership-plans/{id}/update', 'Shared\ApiMembershipPlanController@update');
    Route::delete('membership-plans/{id}/delete', 'Shared\ApiMembershipPlanController@destroy');

    // Route::get('membership-plans/usertype/{usertype}', 'Shared\ApiMembershipPlanController@getSpecificUserPlans');
    Route::post('update-membership-plan', 'Shared\ApiMembershipPlanController@updateMembershipPlan');

    // Save Job Requests APIs
    Route::get('job/save', 'JobRequest\ApiJobRequestController@getAllSaveJobRequests');
    Route::post('job/save', 'JobRequest\ApiJobRequestController@saveJobRequest');
    Route::delete('job/save/{id}/delete', 'JobRequest\ApiJobRequestController@deleteJobRequest');

    // Place Job Offers APIs
    Route::get('user/joboffers', 'JobOffer\ApiJobOfferController@index');
    Route::post('user/joboffers', 'JobOffer\ApiJobOfferController@store');

    // Messages Routes
    Route::get('user/{reciver_id}/messages', 'Chat\MessagesController@index');
    Route::post('user/{reciver_id}/messages', 'Chat\MessagesController@store');
    Route::post('user/messages/{message_id}/update', 'Chat\MessagesController@update'); // to update custom offer status
    Route::post('user/messages/{message_id}/read', 'Chat\MessagesController@markAsRead');
    Route::post('user/messages/{reciver_id}/readall', 'Chat\MessagesController@markAsReadALlMessages');

    // Chat Users Routes 
    Route::get('user/chat-users', 'Chat\ChatUsersController@index');
    Route::get('user/chat-users/{chatUser}/userdata', 'Chat\ChatUsersController@getChatUserData');
    Route::post('user/chat-users', 'Chat\ChatUsersController@store');
    Route::post('user/chat-users/{chatUser}/update', 'Chat\ChatUsersController@update');
    Route::delete('user/chat-users/{chatUser}', 'Chat\ChatUsersController@destroy');

    // Report & Spam Messages Routes
    Route::post('user/report-spam-message', 'Chat\RepostMessagesController@store');
    Route::delete('user/report-spam-message/{messageID}', 'Chat\RepostMessagesController@destroy');

    // Quick Responses Routes
    Route::get('user/quick-responses', 'Chat\QuickResponsesController@index');
    Route::post('user/quick-responses', 'Chat\QuickResponsesController@store');
    Route::post('user/quick-responses/{quickResponse}/update', 'Chat\QuickResponsesController@update');
    Route::delete('user/quick-responses/delete-all', 'Chat\QuickResponsesController@destroyAllQuickResponses');
    Route::delete('user/quick-responses/{quickResponse}', 'Chat\QuickResponsesController@destroy');

    // User Notifications
    Route::post('user/notifications', 'User\ApiUserNotificationController@index');
    Route::post('user/notifications/read-it', 'User\ApiUserNotificationController@markNotificationAsRead');

    // User Orders Routes APIs
    Route::get('user/orders/buyer/{status?}', 'Order\ApiUserOrdersController@getBuyerSideOrders'); // to get specific status orders just pass the status in url at end
    Route::get('user/orders/seller/{status?}', 'Order\ApiUserOrdersController@getSellerSideOrders'); // to get specific status orders just pass the status in url at end
    Route::get('user/orders/status/{status}/days/{days}', 'Order\ApiUserOrdersController@getOrdersInSpecificTime'); // get specific status orders placed in specific time (e.g,  completed orders of last week time = 7 )
    Route::get('user/orders/{order_id}/get-data', 'Order\ApiUserOrdersController@show');
    Route::post('user/orders/{amountToCutFromAccount}', 'Order\ApiUserOrdersController@store'); // type with paypal
    Route::post('user/orders/{order_id}/update', 'Order\ApiUserOrdersController@update');
    Route::post('user/orders/{order_id}/update-status/{status}', 'Order\ApiUserOrdersController@updateOrderStatus');
    Route::delete('user/orders/{order_id}/delete', 'Order\ApiUserOrdersController@destroy');
    Route::post('user/orders/{order_id}/update', 'Order\ApiUserOrdersController@update');
    Route::post('user/orders/{orderNo}/submit-requirements', 'Order\ApiUserOrdersController@placeOrderRequirements');
    Route::post('user/orders/{orderNo}/place-delivery', 'Order\ApiOrderDeliveryController@placeOrderDelivery');
    Route::post('user/orders/{orderID}/accept-delivery/{orderDeliveryID}', 'Order\ApiOrderDeliveryController@acceptOrderDelivery');
    Route::post('user/orders/{orderID}/reject-delivery/{orderDeliveryID}', 'Order\ApiOrderDeliveryController@askOrderDeliveryRevision');

    // Cancel Order Routes APIs
    Route::post('cancel-order/{orderID}', 'Order\ApiOrderCancelRequestController@store');
    Route::post('cancel-order/{id}/accept-request/{orderID}', 'Order\ApiOrderCancelRequestController@acceptRequest');
    Route::post('cancel-order/{id}/reject-request/{orderID}', 'Order\ApiOrderCancelRequestController@rejectRequest');

    // Check User Balance Before placing order
    Route::post('user/orders/{orderPrice}/check-balance-for-order', 'Order\ApiUserOrdersController@checkUserBalance');

    // Orders Chat Routes APIs
    Route::get('user/orders/{orderID}/chat', 'Order\ApiOrderChatController@index');
    Route::post('user/orders/{orderID}/chat', 'Order\ApiOrderChatController@store');

    // Orders Feedback Routes APIs
    Route::post('user/orders/{orderID}/feedback/{userIs}', 'Order\ApiOrderFeedbackController@addFeedback'); // userIs = 'seller' | 'buyer'

    // Orders Support Routes APIs
    Route::get('user/orders-support', 'Order\ApiOrderSupportController@index');
    Route::post('user/orders-support/{orderID}', 'Order\ApiOrderSupportController@store');
    Route::get('user/orders-support/{id}/getdata', 'Order\ApiOrderSupportController@show');
    Route::post('user/orders-support/{id}/update', 'Order\ApiOrderSupportController@update');
    Route::delete('user/orders-support/{id}/delete', 'Order\ApiOrderSupportController@destroy');

    // Orders tip Routes APIs
    Route::get('user/orders-tip', 'Order\ApiOrderTipController@index');
    Route::post('user/orders-tip/{orderID}', 'Order\ApiOrderTipController@store');
    Route::get('user/orders-tip/{id}/getdata', 'Order\ApiOrderTipController@show');
    Route::post('user/orders-tip/{id}/update', 'Order\ApiOrderTipController@update');
    Route::delete('user/orders-tip/{id}/delete', 'Order\ApiOrderTipController@destroy');

    // User Earnings Routes APIs
    Route::get('user/orders-earnings/amount/{days?}', 'Order\ApiUserEarningsController@userEarnings'); // give number of days in days parameter, default is 30
    Route::get('user/orders-pending_clearance/amount/{days?}', 'Order\ApiUserEarningsController@userPendingClearance'); // give number of days in days parameter, default is 30
    Route::get('user/orders-cancelled/amount/{days?}', 'Order\ApiUserEarningsController@userCancelled'); // give number of days in days parameter, default is 30
    Route::get('user/orders-withdrawable/amount/{days?}', 'Order\ApiUserEarningsController@userWithdrawable'); // give number of days in days parameter, default is 30
    Route::get('user/orders-cleared/amount/{days?}', 'Order\ApiUserEarningsController@userCleared'); // give number of days in days parameter, default is 30

});

// ********************************************************************************
// ***************************  Publich APIs  *************************************
// ********************************************************************************
Route::group([
    // 'middleware' => 'auth:sanctum',
    'namespace' => 'Api'
], function () {
    // Home page data
    Route::get('home', 'Home\ApiHomeController@index');
    // User Profile
    Route::get('user/{userID}/profile', 'User\ApiUserController@getSpecificUserProfileData');

    // User Gigs
    Route::get('user/{userID}/gigs/status/{status?}', 'Gig\ApiUserGigController@getOtherUserSpecificStatusGigs'); // get gigs of specific status // default is publish
    Route::get('user/{username}/gig/{slug}/preview', 'Gig\ApiUserGigController@getOtherUserGigPreviewDetail'); // this is to show gig publicaly

    // Gigs Listing Page - All System Gigs Page Routes APIs
    Route::post('all-available-gigs', 'Gig\ApiGigListingController@index');
    Route::get('get-gig-listing-filters', 'Gig\ApiGigListingController@getListingFilters');
    Route::get('get-parent-categories', 'Gig\ApiGigListingController@getParentCategories');
    Route::get('get-child-category', 'Gig\ApiGigListingController@getParentCategories');
    Route::get('gig-category/{slug}/detail', 'Gig\ApiGigListingController@getGigCategoryDetail');
});

// use Ixudra\Curl\Facades\Curl;

// Route::post('test-payout', function () {

//     $paypalPayoutApiUser = "AWEPVBZen2HJ5mrRxf__LOG6UI2xSOXK5Z4t1LNr0f5NDzYAT-NTrx9fJ7QTGD8AK9XZIP2Uq4pFHn-p";
//     $paypalPayoutApiPassword = "EI_JQdSrtUln5Tc050mr6Z3NvNu65uEZLwSdV-pnC-GGUku91_1SV7SrerCJFOHSF95CZhA9xVEmcC0B";
//     $result = Curl::to("https://api.sandbox.paypal.com/v1/oauth2/token")
//         ->withHeaders(
//             array(
//                 'Accept-Language: en_US',
//                 'Accept: application/json',
//                 'Content-Type:application/x-www-form-urlencoded'
//             )
//         )
//         ->withOption("USERPWD", "$paypalPayoutApiUser:$paypalPayoutApiPassword")
//         ->withData(
//             array(
//                 "grant_type:client_credentials"
//             )
//         )
//         ->asJson()
//         ->returnResponseObject()
//         ->post();

//     return response()->json(['message' => $result], 200);
// });
