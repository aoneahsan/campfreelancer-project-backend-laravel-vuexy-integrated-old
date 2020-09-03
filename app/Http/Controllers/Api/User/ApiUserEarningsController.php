<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserEarningResource;
use App\Http\Resources\User\UserTransactionalResource;
use App\Model\User\UserTransactionLog;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiUserEarningsController extends Controller
{
    public function index(Request $request)
    {
        $userData = User::where('id', $request->user()->id)
            ->with('account')
            ->withCount(
                [
                    'netIncome' => function ($query) {
                        $query->select(DB::raw('sum(amount)'));
                    },
                    'withdrawn' => function ($query) {
                        $query->select(DB::raw('sum(amount)'));
                    },
                    'usedForPurchases' => function ($query) {
                        $query->select(DB::raw('sum(amount)'));
                    },
                    'pendingClearance' => function ($query) {
                        $query->select(DB::raw('sum(seller_earning)'));
                    },
                    'cancelledOrdersAsSeller' => function ($query) {
                        $query->select(DB::raw('sum(seller_earning)'));
                    }
                ]
            )
            ->first();
        // return response()->json(['data' => $userData], 500);
        return response()->json(['data' => new UserEarningResource($userData)], 200);
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
