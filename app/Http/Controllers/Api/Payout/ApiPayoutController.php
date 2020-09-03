<?php

namespace App\Http\Controllers\Api\Payout;

use App\Http\Controllers\Controller;
use App\Http\Resources\Payout\PayoutRequestResource;
use App\Model\App\AppSetting;
use App\Model\Payout\PayoutRequest;
use App\Model\User\UserAccount;
use App\Model\User\UserTransactionLog;
use Carbon\Carbon;
use Illuminate\Http\Request;

use Ixudra\Curl\Facades\Curl;

class ApiPayoutController extends Controller
{
    public function checkBalanceAndWithdrawAmount(Request $request)
    {
        $userAccount = UserAccount::where('user_id', $request->user()->id)->first();
        $appSetting = AppSetting::first();

        $userbalance = $userAccount->balance;

        $minWithdrawBalanceForPaypalWithdraw = $appSetting->minimum_withdrawable_amount_for_paypalWithdraw;
        $minWithdrawBalanceForPayoneerWithdraw = $appSetting->minimum_withdrawable_amount_for_payoneerWithdraw;
        $minWithdrawBalanceForManualWithdraw = $appSetting->minimum_withdrawable_amount_for_manualWithdraw;

        $userCanWithdraw = null;
        if ($request->payout_method == 'paypal_payout') {
            $userCanWithdraw = !!($userbalance >= $minWithdrawBalanceForPaypalWithdraw);
        } else if ($request->payout_method == 'payoneer_payout') {
            $userCanWithdraw = !!($userbalance >= $minWithdrawBalanceForPayoneerWithdraw);
        } else if ($request->payout_method == 'manual_payout') {
            $userCanWithdraw = !!($userbalance >= $minWithdrawBalanceForManualWithdraw);
        } else {
            return response()->json(['message' => 'Invalid Payout Method'], 400);
        }

        $responseData = [
            'userbalance' => $userbalance,
            'minWithdrawBalanceForPaypalWithdraw' => $minWithdrawBalanceForPaypalWithdraw,
            'minWithdrawBalanceForPayoneerWithdraw' => $minWithdrawBalanceForPayoneerWithdraw,
            'minWithdrawBalanceForManualWithdraw' => $minWithdrawBalanceForManualWithdraw,
            'userCanWithdraw' => $userCanWithdraw
        ];
        return response()->json(['data' => $responseData], 200);
    }

    public function store(Request $request)
    {
        // don't delete anything from this function currently working on it
        $user_id = $request->user()->id;
        $userAccount = UserAccount::where('user_id', $user_id)->first();
        $appSetting = AppSetting::first();

        $userbalance = $userAccount->balance;

        $minWithdrawBalanceForPaypalWithdraw = $appSetting->minimum_withdrawable_amount_for_paypalWithdraw;
        $minWithdrawBalanceForPayoneerWithdraw = $appSetting->minimum_withdrawable_amount_for_payoneerWithdraw;
        $minWithdrawBalanceForManualWithdraw = $appSetting->minimum_withdrawable_amount_for_manualWithdraw;

        $userCanWithdraw = true;
        // if ($request->payout_method == 'paypal_payout') {
        //     $userCanWithdraw = !!($userbalance >= $minWithdrawBalanceForPaypalWithdraw);
        // } else if ($request->payout_method == 'payoneer_payout') {
        //     $userCanWithdraw = !!($userbalance >= $minWithdrawBalanceForPayoneerWithdraw);
        // } else if ($request->payout_method == 'manual_payout') {
        //     $userCanWithdraw = !!($userbalance >= $minWithdrawBalanceForManualWithdraw);
        // } else {
        //     return response()->json(['message' => 'Invalid Payout Method'], 400);
        // }

        if (!!$userCanWithdraw) {
            // $currentDate = Carbon::now();

            // $user_balance_before_placing_request = $userbalance;
            // $user_balance_after_placing_request = $userbalance - $request->payout_request_amount;

            // $userAccount->update([
            //     'balance' => $user_balance_after_placing_request
            // ]);

            $paypalPayoutApiUser = $appSetting->paypal_payout_api_user;
            $paypalPayoutApiPassword = $appSetting->paypal_payout_api_password;

            $result = Curl::to("https://api.sandbox.paypal.com/v1/oauth2/token")
                ->withHeaders(array('Accept-Language: en_US', 'Accept: application/json'))
                ->withContentType('application/x-www-form-urlencoded')
                ->withOption("USERPWD", "$paypalPayoutApiUser:$paypalPayoutApiPassword")
                ->withData(array("grant_type" => "client_credentials"))
                ->asJson()
                ->returnResponseObject()
                ->post();

            return response()->json(['message' => $result], 500);

            // $item = PayoutRequest::create([
            //     'user_id' => $user_id,
            //     'user_balance_before_placing_request' => $user_balance_before_placing_request,
            //     'user_balance_after_placing_request' => $user_balance_after_placing_request,
            //     'payout_request_amount' => $request->has('payout_request_amount') ? $request->payout_request_amount : null,
            //     'payout_request_created_at' => $currentDate,
            //     // 'payout_request_completed_at' => $request->has('payout_request_completed_at') ? $request->payout_request_completed_at : null,
            //     // 'payout_request_rejected_at' => $request->has('payout_request_rejected_at') ? $request->payout_request_rejected_at : null,
            //     'payout_method' => $request->has('payout_method') ? $request->payout_method : null,
            //     'status' => $request->has('status') ? $request->status : null,
            //     'paypal_response' => $request->has('paypal_response') ? $request->paypal_response : null,
            //     'payoneer_response' => $request->has('payoneer_response') ? $request->payoneer_response : null,
            // ]);

            // UserTransactionLog::create([
            //     'user_id' => $user_id,
            //     'amount' => $request->has('payout_request_amount') ? $request->payout_request_amount : null,
            //     'transaction_log_type' => 'withdrawal_initiated',
            //     'log_created_at' => $currentDate
            // ]);

            // if ($item) {
            //     return response()->json(['data' => new PayoutRequestResource($item), 'userNewBalance' => $user_balance_after_placing_request], 200);
            // } else {
            //     return response()->json(['message' => "Error while creating PayoutRequest."], 500);
            // }
        } else {
            return response()->json(['message' => "Invalid Payout method or User don't have Enough Balance."], 400); // 400 not allowed
        }
    }

    public function markPayoutRequestAsCompleted(Request $request, $id)
    {
        //
    }

    public function markPayoutRequestAsRejected(Request $request, $id)
    {
        //
    }
}
