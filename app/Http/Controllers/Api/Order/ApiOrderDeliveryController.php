<?php

namespace App\Http\Controllers\Api\Order;

use App\Events\Order\UserNotificationEvent;
use App\Http\Controllers\Controller;
use App\Http\Resources\Order\OrderDeliveryResource;
use App\Model\App\AppSetting;
use App\Model\Order\Order;
use App\Model\Order\OrderDelivery;
use App\Model\User\UserTransactionLog;
use App\Notifications\UserNotification;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ApiOrderDeliveryController extends Controller
{
    public function index($orderID)
    {
        $items = OrderDelivery::where('order_id', $orderID)->get();
        return response()->json(['data' => OrderDeliveryResource::collection($items)], 200);
    }

    public function placeOrderDelivery(Request $request, $order_id)
    {
        $order = Order::where('order_number', $order_id)->first();
        $currentDate = Carbon::now();
        if ($order) {
            $order->update([
                'status' => 'delivered',
                'order_delivered_at' => Carbon::now()
            ]);

            $filePath = null;
            if ($request->hasFile('file')) {
                $filePath = $request->file('file')->store('orderdelivery');
            }

            $item = OrderDelivery::create([
                'order_id' => $order->id,
                'buyer_id' => $order->buyer_id,
                'seller_id' => $order->seller_id,
                'message' => $request->message,
                'file_path' => $filePath,
                'file_type' => $request->file_type,
                'status' => $request->has('status') ? $request->status : 'publish',
                'delivery_placed_at' => $currentDate
            ]);

            if ($item) {
                $delivery = OrderDelivery::where('id', $item->id)->with('seller', 'buyer', 'order')->first();
                $orderDelivery = new OrderDeliveryResource($delivery);
                if ($item->status == 'publish') {
                    $buyer = User::where('id', $order->buyer_id)->first();
                    $buyer->notify(new UserNotification($orderDelivery, 'order_delivery', 'buyer'));
                }
                // return response()->json(['data' => new UserOrderResource($responseOrder)], 200);
                return response()->json(['data' => $orderDelivery], 200);
            } else {
                return response()->json(['message' => "Error Occured, while updating order."], 500);
            }
        } else {
            return response()->json(['message' => "No order found with this ID."], 404);
        }
    }

    public function acceptOrderDelivery(Request $request, $orderID, $orderDeliveryID) // ask for order deliver revision
    {
        $orderDelivery = OrderDelivery::where('id', $orderDeliveryID)->first();
        $order_completed_at = Carbon::now();
        $daysRequiredForClearnce = AppSetting::first()->seller_amount_pending_clearnace_time;
        $amount_will_clear_at = $order_completed_at->addDays($daysRequiredForClearnce);

        $orderData = Order::where('id', $orderID)->first();
        $orderData->update([
            'status' => 'completed', // dont mark 'is_cleared' 'true' here it will mark 'true' automatically by "task schedule" after three days.
            'order_delivered_at' => $orderDelivery->created_at,
            'order_completed_at' => $order_completed_at,
            'amount_will_clear_at' => $amount_will_clear_at
        ]);

        $item = $orderDelivery->update([
            'status' => 'completed'
        ]);

        UserTransactionLog::create([
            'user_id' => $orderData->seller_id,
            'order_id' => $orderData->id,
            'order_number' => $orderData->order_number,
            'transaction_log_type' => 'order_revenue',
            'amount' => $orderData->seller_earning,
            'log_created_at' => $order_completed_at,
            'order_earning_clearnace_date' => $amount_will_clear_at
        ]);

        if ($item) {
            $seller = User::where('id', $orderDelivery->seller_id)->first();
            $orderDeliveryUpdated = OrderDelivery::where('id', $orderDeliveryID)->first();
            $orderDeliveryResource = new OrderDeliveryResource($orderDeliveryUpdated);
            $seller->notify(new UserNotification($orderDeliveryResource, 'order_delivery_accepted', 'seller'));
            return response()->json(['data' => $orderDeliveryResource], 200);
        } else {
            return response()->json(['message' => "Error Occured, while updating order."], 500);
        }
    }

    public function askOrderDeliveryRevision(Request $request, $orderID, $orderDeliveryID) // ask for order deliver revision
    {
        Order::where('id', $orderID)->update([
            'status' => 'in_revision'
        ]);

        $orderDelivery = OrderDelivery::where('id', $orderDeliveryID)->first();
        $item = $orderDelivery->update([
            'status' => 'in_revision',
            'revision' => $request->has('revision') ? $request->revision : null
        ]);

        if ($item) {
            $seller = User::where('id', $orderDelivery->seller_id)->first();
            $orderDeliveryUpdated = OrderDelivery::where('id', $orderDeliveryID)->first();
            $orderDeliveryResource = new OrderDeliveryResource($orderDeliveryUpdated);
            $seller->notify(new UserNotification($orderDeliveryResource, 'order_delivery_revision', 'seller'));
            return response()->json(['data' => $orderDeliveryResource], 200);
        } else {
            return response()->json(['message' => "Error Occured, while updating order."], 500);
        }
    }
}
