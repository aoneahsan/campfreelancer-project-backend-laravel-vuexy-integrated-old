<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserAnalyticsResource;
use App\Http\Resources\User\UserTransactionalResource;
use App\Model\User\UserTransactionLog;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApiUserAnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $currentDate = getdate();
        $getOrderInThisRange = Carbon::today()->subDays($currentDate['mday'] - 1);
        // $userData = User::where('id', $request->user()->id)
        //     ->with('account')
        //     ->withCount(['withdrawn' => function ($query) {
        //         $query->select(DB::raw('sum(amount)'));
        //     }])
        //     ->withCount(['usedForPurchases' => function ($query) {
        //         $query->select(DB::raw('sum(amount)'));
        //     }])
        //     ->withCount(['pendingClearance' => function ($query) {
        //         $query->select(DB::raw('sum(seller_earning)'));
        //     }])
        //     ->withCount(['cancelledOrdersAsSeller' => function ($query) {
        //         $query->select(DB::raw('sum(seller_earning)'));
        //     }])
        //     ->first();
        $transactionalLogType = "order_revenue";
        $transactionalLogType2 = "membership_plan";
        // UserRelationsTrait::
        $userData = User::where('id', $request->user()->id)
            ->with(
                [
                    'ordersAsSeller' => function ($query) {
                        $query->whereIn('status', ['active', 'completed', 'cancelled'])->orderBy('created_at');
                    },
                    'transactionalLog' => function ($query) use ($transactionalLogType) {
                        $query->transanctionalType($transactionalLogType);
                    }
                ]
            )
            ->withCount(
                [
                    'netIncome' => function ($query) {
                        $query->select(DB::raw('sum(amount)'));
                    },
                    'completedOrdersAsSellerSpecificRange' => function ($query) use ($getOrderInThisRange) {
                        $query->where('created_at', '>=', $getOrderInThisRange)->select(DB::raw('sum(seller_earning)'));
                    },
                    'communicationRatingAsSeller' => function ($query) {
                        $query->select(DB::raw('avg(buyer_rating_sellerCommunication)'));
                    },
                    'serviceAsDescribedRatingAsSeller' => function ($query) {
                        $query->select(DB::raw('avg(buyer_rating_serviceAsDescribed)'));
                    },
                    'recommendRatingAsSeller' => function ($query) {
                        $query->select(DB::raw('avg(buyer_rating_sellerRecommended)'));
                    },
                    'avarageSellingPrice' => function ($query) {
                        $query->select(DB::raw('avg(seller_earning)'));
                    },
                    'fiveStartRatingAsSeller',
                    'fourStartRatingAsSeller',
                    'threeStartRatingAsSeller',
                    'twoStartRatingAsSeller',
                    'oneStartRatingAsSeller',
                    'pendingFeedbackAsSeller',
                    'completedOrdersAsSeller'
                ]
            )
            // ->withAnalytics()
            ->withAnalytics(Auth::user())
            ->first();
        return dd($userData->toArray());
        return response()->json(['data' => new UserAnalyticsResource($userData)], 200);
        // return response()->json(['data' => '$userData'], 200);
    }

    public function getTransactionalLogs(Request $request, $days = null)
    {
        $items = UserTransactionLog::where('user_id', $request->user()->id);
        if (!!$days) {
            $date = Carbon::now()->subDays($days);
            $items->where('created_at', '<=', $date);
        }
        $result = $items->get();
        return response()->json(['data' => UserTransactionalResource::collection($result)], 200);
    }
}
