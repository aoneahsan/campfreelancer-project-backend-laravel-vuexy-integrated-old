<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Controller;
use App\Http\Resources\Order\UserOrderResource;
use App\Model\Order\Order;
use App\Model\Order\OrderCancelRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ApiOrderCancelRequestController extends Controller
{
    public function store(Request $request, $orderID)
    {
        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('orderchat');
        }

        $alreadyPlaced = OrderCancelRequest::where('order_id', $orderID)->where('status', 'publish')->first();
        if (!!$alreadyPlaced) {
            return response()->json(['message' => 'Already one request placed wait for that request response to create new request.'], 400);
        }

        $item = OrderCancelRequest::create([
            'user_id' => $request->has('user_id') ? $request->user_id : null,
            'seller_id' => $request->has('seller_id') ? $request->seller_id : null,
            'buyer_id' => $request->has('buyer_id') ? $request->buyer_id : null,
            'order_id' => $orderID,
            'order_number' => $request->has('order_number') ? $request->order_number : null,
            'type' => $request->has('type') ? $request->type : null,
            'reason' => $request->has('reason') ? json_encode($request->reason) : null,
            'file_path' => $filePath
        ]);

        if ($item) {
            $order = Order::where('id', $orderID)->with('buyer', 'seller', 'gig', 'deliveries', 'cancelRequests')->first();
            return response()->json(['data' => new UserOrderResource($order)], 200);
        }
        else {
            return response()->json(['message' => "Error Occured, while creating order support request."], 500);
        }
    }

    public function acceptRequest(Request $request, $id, $orderID)
    {
        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('orderchat');
        }
        $currentDate = Carbon::now();

        $order = Order::where('id', $orderID)->first();
        $item = OrderCancelRequest::where('id', $id)->first();

        $order->update([
            'status' => 'cancelled',
            'order_cancel_reason' => $item->reason,
            'order_cancelled_at' => $currentDate
        ]);

        $result = $item->update([
            'status' => 'accepted',
            'response_message' => $request->has('response_message') ? $request->response_message : $item->response_message,
            'response_at' => $currentDate
        ]);

        if ($result) {
            $order = Order::where('id', $orderID)->with('buyer', 'seller', 'gig', 'deliveries', 'cancelRequests')->first();
            return response()->json(['data' => new UserOrderResource($order)], 200);
        }
        else {
            return response()->json(['message' => "Error Occured, while accepting order cancel request."], 500);
        }
    }

    public function rejectRequest(Request $request, $id, $orderID)
    {
        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('orderchat');
        }
        $currentDate = Carbon::now();

        $order = Order::where('id', $orderID)->first();

        $item = OrderCancelRequest::where('id', $id)->first();
        $result = $item->update([
            'status' => 'rejected',
            'response_message' => $request->has('response_message') ? $request->response_message : $item->response_message,
            'response_at' => $currentDate
        ]);

        if ($result) {
            $order = Order::where('id', $orderID)->with('buyer', 'seller', 'gig', 'deliveries', 'cancelRequests')->first();
            return response()->json(['data' => new UserOrderResource($order)], 200);
        }
        else {
            return response()->json(['message' => "Error Occured, while rejecting order cancel request."], 500);
        }
    }
}
