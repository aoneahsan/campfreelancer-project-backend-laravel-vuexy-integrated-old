<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApiUserEarningsController extends Controller
{
    public function userEarnings(Request $request, $days = 30)
    {
        $amount = $request->user()->ordersEarnings($days);
        return response()->json(['data' => $amount], 200);
    }

    public function userPendingClearance(Request $request, $days = 30)
    {
        $amount = $request->user()->ordersPendingClearance($days);
        return response()->json(['data' => $amount], 200);
    }

    public function userCancelled(Request $request, $days = 30)
    {
        $amount = $request->user()->ordersCancelled($days);
        return response()->json(['data' => $amount], 200);
    }

    public function userWithdrawable(Request $request, $days = 30)
    {
        $amount = $request->user()->ordersWithdrawable($days);
        return response()->json(['data' => $amount], 200);
    }

    public function userCleared(Request $request, $days = 30)
    {
        $amount = $request->user()->ordersCleared($days);
        return response()->json(['data' => $amount], 200);
    }
}
