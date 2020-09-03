<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Controller;
use App\Http\Resources\Order\OrderDeliveryResource;
use Illuminate\Http\Request;

use App\Model\Order\Order;

use App\Http\Resources\Order\UserOrderResource;
use App\Http\Resources\Order\UserOrdersResource;
use App\Model\App\AppSetting;
use App\Model\Order\OrderDelivery;
use App\Model\User\UserAccount;
use App\Model\User\UserTransactionLog;
use App\Notifications\UserNotification;
use App\User;
use Carbon\Carbon;

class ApiUserOrdersController extends Controller
{
    public function getBuyerSideOrders(Request $request, $status = null)
    {
        $user_id = $request->user()->id;
        $orders = Order::where('buyer_id', $user_id);
        if (!!$status) {
            $orders->where('status', $status);
        }
        $finalData = $orders->with('seller', 'deliveries')->get();
        return response()->json(['data' => UserOrdersResource::collection($finalData)], 200);
    }

    public function getSellerSideOrders(Request $request, $status = null)
    {
        $user_id = $request->user()->id;
        $orders = Order::where('seller_id', $user_id);
        if (!!$status) {
            $orders->where('status', $status);
        }
        $finalData = $orders->with('buyer')->get();
        return response()->json(['data' => UserOrdersResource::collection($finalData)], 200);
    }

    public function getOrdersInSpecificTime(Request $request, $status, $days)
    {
        $user_id = $request->user()->id;
        $orders = Order::where('buyer_id', $user_id);
        if (!!$status) {
            $orders->where('status', $status);
        }
        if (!!$days) {
            $date = Carbon::today()->subDays($days);
            $orders->where('created_at', '>=', $date);
        }
        $finalData = $orders->with('buyer')->get();
        return response()->json(['data' => UserOrdersResource::collection($finalData)], 200);
    }

    public function store(Request $request, $amountToCutFromAccount)
    {
        $user_id = $request->user()->id;
        $useraccount = UserAccount::where('user_id', $user_id)->first();
        $newBalance = floor($useraccount->balance - $amountToCutFromAccount);
        $useraccount->update([
            'balance' => $newBalance
        ]);

        $amountToCut = AppSetting::first()->order_commission_from_seller; // this will give the percentage value mean if this return 20 then take 20% from seller
        $percentageToTake = (($request->price * $amountToCut) / 100);
        $seller_earning = floor($request->price - $percentageToTake);
        $delivery_due_date = Carbon::now()->addDays($request->order_time_in_days);

        $neworder = Order::create([
            'buyer_id' => $user_id,
            'seller_id' => $request->has('seller_id') ? $request->seller_id : null,
            'gig_id' => $request->has('gig_id') ? $request->gig_id : null,
            'order_source' => $request->has('order_source') ? $request->order_source : 'custom-offer',
            'order_number' => uniqid(),
            'order_title' => $request->has('order_title') ? $request->order_title : null,
            'order_description' => $request->has('order_description') ? $request->order_description : null,
            'order_time' => $request->has('order_time_in_days') ? Carbon::now()->addDays($request->order_time_in_days) : null,
            'order_time_in_days' => $request->has('order_time_in_days') ? $request->order_time_in_days : null,
            'price' => $request->has('price') ? $request->price : null,
            'seller_earning' => $seller_earning,
            'revisions' => $request->has('revisions') ? $request->revisions : null,
            'status' => $request->has('status') ? $request->status : null,
            'order_rated_at' => $request->has('order_rated_at') ? $request->order_rated_at : null,
            'ask_for_requirements' => $request->has('ask_for_requirements') ? $request->ask_for_requirements : null,
            'requirements_submited_at' => $request->has('ask_for_requirements') ? ($request->ask_for_requirements ? null : Carbon::now()) : Carbon::now(),
            'order_requirement_title' => $request->has('order_requirement_title') ? $request->order_requirement_title : null,
            'order_requirement_description' => $request->has('order_requirement_description') ? $request->order_requirement_description : null,
            'is_favorite' => $request->has('is_favorite') ? $request->is_favorite : null,
            'order_delivery_date' => $delivery_due_date,
            'order_delivered_at' => $request->has('order_delivered_at') ? $request->order_delivered_at : null,
            'order_cancelled_at' => $request->has('order_cancelled_at') ? $request->order_cancelled_at : null,
            'order_cancel_reason' => $request->has('order_cancel_reason') ? $request->order_cancel_reason : null,
            'checkout_response' => $request->has('checkout_response') ? json_encode($request->checkout_response) : null
        ]);

        UserTransactionLog::create([
            'user_id' => $user_id,
            'order_id' => $neworder->id,
            'order_number' => $neworder->order_number,
            'amount' => $neworder->price,
            'transaction_log_type' => 'order_placed',
            'log_created_at' => $neworder->created_at
        ]);

        if ($neworder) {
            $orderResourse = new UserOrderResource($neworder);
            $seller = User::where('id', $request->seller_id)->first();
            $seller->notify(new UserNotification($orderResourse, 'new_order_placed', 'seller'));
            return response()->json(['data' => $orderResourse], 200);
        } else {
            return response()->json(['message' => "Error Occured, while creating order."], 500);
        }
    }

    public function show($order_id)
    {
        // $order = Order::where('id', $order_id)->orWhere('order_number', $order_id)->with('buyer', 'seller', 'gig')->first();
        $order = Order::where('order_number', $order_id)->with('buyer', 'seller', 'gig', 'deliveries', 'cancelRequests')->first();
        if ($order) {
            return response()->json(['data' => new UserOrderResource($order)], 200);
        } else {
            return response()->json(['message' => "No order found with this ID."], 404);
        }
    }

    public function update(Request $request, $order_id)
    {
        $order = Order::where('order_number', $order_id)->first();
        if ($order) {
            $updatedOrder = $order->update([
                // 'order_source' => $request->has('order_source') ? $request->order_source : $order->order_source,
                // 'order_title' => $request->has('order_title') ? $request->order_title : $order->order_title,
                // 'order_description' => $request->has('order_description') ? $request->order_description : $order->order_description,
                // 'order_time' => $request->has('order_time_in_days') ? Carbon::parse($order->order_time)->addSeconds(Carbon::now()->addDays($request->order_time_in_days)->diffInSeconds()) : $order->order_time,
                // 'order_time_in_days' => $request->has('order_time_in_days') ? $request->order_time_in_days : $order->order_time_in_days,
                // 'price' => $request->has('price') ? $request->price : $order->price,
                // 'revisions' => $request->has('revisions') ? $request->revisions : $order->revisions,
                'status' => $request->has('status') ? $request->status : $order->status,
                'order_rated_at' => $request->has('order_rated_at') ? $request->order_rated_at : $order->order_rated_at,
                'ask_for_requirements' => $request->has('ask_for_requirements') ? $request->ask_for_requirements : $order->ask_for_requirements,
                'requirements_submited_at' => $request->has('requirements_submited_at') ? ($request->requirements_submited_at ? Carbon::now() : $order->requirements_submited_at) : $order->requirements_submited_at,
                'order_requirement_title' => $request->has('order_requirement_title') ? $request->order_requirement_title : $order->order_requirement_title,
                'order_requirement_description' => $request->has('order_requirement_description') ? $request->order_requirement_description : $order->order_requirement_description,
                'is_favorite' => $request->has('is_favorite') ? $request->is_favorite : $order->is_favorite,
                'order_delivery_date' => $request->has('order_delivery_date') ? $request->order_delivery_date : $order->order_delivery_date,
                'order_delivered_at' => $request->has('order_delivered_at') ? $request->order_delivered_at : $order->order_delivered_at,
                'order_cancelled_at' => $request->has('order_cancelled_at') ? $request->order_cancelled_at : $order->order_cancelled_at,
                'order_cancel_reason' => $request->has('order_cancel_reason') ? $request->order_cancel_reason : $order->order_cancel_reason
            ]);
            $responseOrder = Order::where('id', $order_id)->orWhere('order_number', $order_id)->with('buyer', 'seller', 'gig')->first();
            if ($updatedOrder) {
                return response()->json(['data' => new UserOrderResource($responseOrder)], 200);
            } else {
                return response()->json(['message' => "Error Occured, while updating order."], 500);
            }
        } else {
            return response()->json(['message' => "No order found with this ID."], 404);
        }
    }

    public function updateOrderStatus(Request $request, $order_id, $status)
    {
        $order = Order::where('id', $order_id)->orWhere('order_number', $order_id)->first();
        if ($order) {
            if (!!$status) {
                $data = Carbon::now();
            }
            if ($status == 'delivered') {
                $updatedOrder = $order->update([
                    'status' => $status,
                    'order_delivery_date' => $data,
                    'order_delivered_at' => $request->has('order_delivered_at') ? $request->order_delivered_at : $order->order_delivered_at,
                    'order_cancelled_at' => $request->has('order_cancelled_at') ? $request->order_cancelled_at : $order->order_cancelled_at,
                    'order_cancel_reason' => $request->has('order_cancel_reason') ? $request->order_cancel_reason : $order->order_cancel_reason
                ]);
            }
            $responseOrder = Order::where('id', $order_id)->orWhere('order_number', $order_id)->with('buyer', 'seller', 'gig')->first();

            if ($updatedOrder) {
                return response()->json(['data' => new UserOrderResource($responseOrder)], 200);
            } else {
                return response()->json(['message' => "Error Occured, while updating order."], 500);
            }
        } else {
            return response()->json(['message' => "No order found with this ID."], 404);
        }
    }

    public function destroy($order_id)
    {
        $order = Order::where('id', $order_id)->orWhere('order_number', $order_id)->delete();
        if ($order) {
            return response()->json(['data' => "Order Deleted!"], 200);
        } else {
            return response()->json(['message' => "No order found with this ID."], 404);
        }
    }

    public function checkUserBalance(Request $request, $orderPrice)
    {
        $user_balance = $request->user()->account->balance;
        $remaining_amount = ceil(+$orderPrice - $user_balance);
        if ($user_balance >= +$orderPrice) {
            $data = [
                'availableBalance' => $user_balance,
                'remainingBalance' => 0
            ];
            return response()->json(['data' => $data], 200);
        } else if ($user_balance < $orderPrice) {
            $data = [
                'availableBalance' => $user_balance,
                'remainingBalance' => $remaining_amount
            ];
            return response()->json(['data' => $data], 200);
        } else {
            return response()->json(['message' => "Error Occired, can't calculate user balance."], 500);
        }
    }

    public function placeOrderRequirements(Request $request, $orderNo)
    {
        $order = Order::where('order_number', $orderNo)->first();
        if ($order) {
            $updatedOrder = $order->update([
                'requirements_submited_at' => $request->has('requirements_submited_at') ? ($request->requirements_submited_at ? Carbon::now() : $order->requirements_submited_at) : $order->requirements_submited_at,
                'order_requirement_description' => $request->has('order_requirement_description') ? $request->order_requirement_description : $order->order_requirement_description
            ]);
            // $responseOrder = Order::where('order_number', $orderNo)->with('buyer', 'seller', 'gig')->first();
            if ($updatedOrder) {
                $orderresponse = Order::where('order_number', $orderNo)->with('buyer', 'seller', 'gig', 'deliveries')->first();
                $orderResponseResource = new UserOrderResource($orderresponse);
                // $seller = User::where('id', $order->seller_id)->first();
                // $seller->
                return response()->json(['data' => $orderResponseResource], 200);
            } else {
                return response()->json(['message' => "Error Occured, while submiting order requirements order."], 500);
            }
        } else {
            return response()->json(['message' => "No order found with this ID/number."], 404);
        }
    }
}
