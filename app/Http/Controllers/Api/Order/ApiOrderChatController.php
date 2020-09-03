<?php

namespace App\Http\Controllers\Api\Order;

use App\Events\Order\OrderChatEvent;
use App\Http\Controllers\Controller;
use App\Http\Resources\Order\OrderChatResource;
use App\Model\Order\Order;
use App\Model\Order\OrderChat;
use Illuminate\Http\Request;

class ApiOrderChatController extends Controller
{
    public function index($orderID)
    {
        $order = Order::where('id', $orderID)->orWhere('order_number', $orderID)->first();
        if ($order) {
            $items = OrderChat::where('order_id', $order->id)->with('sender', 'reciver')->get();
            return response()->json(['data' => OrderChatResource::collection($items)], 200);
        } else {
            return response()->json(['data' => "Order Not Found!"], 404);
        }
    }

    public function store(Request $request, $orderID)
    {
        $order = Order::where('id', $orderID)->orWhere('order_number', $orderID)->first();
        if ($order) {
            $user_id = $request->user()->id;

            $filePath = null;
            if ($request->hasFile('file')) {
                $filePath = $request->file('file')->store('orderchat');
            }

            $item = OrderChat::create([
                'user_id' => $user_id,
                'order_id' => $order->id,
                'reciver_id' => $request->has('reciver_id') ? $request->reciver_id : null,
                'message' => $request->has('message') ? $request->message : null,
                'type' => $request->has('type') ? $request->type : null,
                'file_type' => $request->has('file_type') ? $request->file_type : null,
                'is_reported' => $request->has('is_reported') ? $request->is_reported : null,
                'is_spammed' => $request->has('is_spammed') ? $request->is_spammed : null,
                'file_path' => $filePath
            ]);

            if ($item) {
                $item->reciver();
                $message = new OrderChatResource($item);
                try {
                    broadcast(new OrderChatEvent($message))->toOthers();
                } catch (\Throwable $th) {
                }
                return response()->json(['data' => $message], 200);
            } else {
                return response()->json(['message' => "Error Occured, while posting order chat message."], 500);
            }
        } else {
            return response()->json(['data' => "Order Not Found!"], 404);
        }
    }
}
