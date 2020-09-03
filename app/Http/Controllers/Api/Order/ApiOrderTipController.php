<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Controller;
use App\Http\Resources\Order\OrderTipResource;
use App\Model\Order\Order;
use App\Model\Order\OrderTip;
use App\Notifications\UserNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ApiOrderTipController extends Controller
{
    public function index()
    {
        $items = OrderTip::all();
        return response()->json(['data' => OrderTipResource::collection($items)], 200);
    }

    public function store(Request $request, $orderID)
    {
        $order = Order::where('id', $orderID)->first();

        $date = Carbon::now();

        $order->update([
            'buyer_placed_tip_at' => $date
        ]);

        $item = OrderTip::create([
            'seller_id' => $order->seller_id,
            'buyer_id' => $order->buyer_id,
            'order_id' => $orderID,
            'tip_amount' => $request->has('tip_amount') ? $request->tip_amount : null,
            'reason' => $request->has('reason') ? $request->reason : null,
            'time' => $date
        ]);

        if ($item) {
            try {
                $item->buyer();
                $itemResource = new OrderTipResource($item);
                $item->seller()->notify(new UserNotification($itemResource, 'order_tip', 'seller'));
            } catch (\Throwable $th) {
            }
            return response()->json(['data' => "Order Supoort Request Created."], 200);
        } else {
            return response()->json(['message' => "Error Occured, while creating order tip request."], 500);
        }
    }

    public function show($id)
    {
        $item = OrderTip::where('id', $id)->first();

        if ($item) {
            return response()->json(['data' => new OrderTipResource($item)], 200);
        } else {
            return response()->json(['message' => "Error Occured, while geting order tip request data 'show() function'."], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $date = Carbon::now();
        $item = OrderTip::where('id', $id)->first();
        $result = $item->update([
            'tip_amount' => $request->has('tip_amount') ? $request->tip_amount : $item->tip_amount,
            'reason' => $request->has('reason') ? $request->reason : $item->reason,
            'time' => $date
        ]);

        if ($result) {
            return response()->json(['data' => "Order Supoort Request Updated."], 200);
        } else {
            return response()->json(['message' => "Error Occured, while updating order tip request."], 500);
        }
    }

    public function destroy($id)
    {
        $item = OrderTip::where('id', $id)->delete();

        if ($item) {
            return response()->json(['data' => "Order Supoort Request Deleted."], 200);
        } else {
            return response()->json(['message' => "Error Occured, while deleting order tip request."], 500);
        }
    }
}
