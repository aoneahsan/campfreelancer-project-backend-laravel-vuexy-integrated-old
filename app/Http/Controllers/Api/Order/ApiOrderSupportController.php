<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Controller;
use App\Http\Resources\Order\OrderSupportResource;
use App\Model\Order\Order;
use App\Model\Order\OrderFeedback;
use App\Model\Order\OrderSupport;
use Illuminate\Http\Request;

class ApiOrderSupportController extends Controller
{
    public function index()
    {
        $items = OrderFeedback::with('buyer', 'seller', 'order')->get();
        return response()->json(['data' => OrderSupportResource::collection($items)], 200);
    }

    public function store(Request $request, $orderID)
    {
        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('orderchat');
        }

        $item = OrderSupport::create([
            'seller_id' => $request->has('seller_id') ? $request->seller_id : null,
            'buyer_id' => $request->has('buyer_id') ? $request->buyer_id : null,
            'order_id' => $orderID,
            'type' => $request->has('type') ? $request->type : null,
            'reason' => $request->has('reason') ? json_encode($request->reason) : null,
            'file_path' => $filePath
        ]);

        if ($item) {
            return response()->json(['data' => "Order Supoort Request Created."], 200);
        }
        else {
            return response()->json(['message' => "Error Occured, while creating order support request."], 500);
        }
    }

    public function show($id)
    {
        $item = OrderSupport::where('id', $id)->first();

        if ($item) {
            return response()->json(['data' => new OrderSupportResource($item)], 200);
        }
        else {
            return response()->json(['message' => "Error Occured, while geting order support request data 'show() function'."], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('orderchat');
        }

        $item = OrderSupport::where('id', $id)->first();
        $result = $item->update([
            'type' => $request->has('type') ? $request->type : $item->type,
            'reason' => $request->has('reason') ? $request->reason : $item->reason,
            'file_path' => $filePath
        ]);

        if ($result) {
            return response()->json(['data' => "Order Supoort Request Updated."], 200);
        }
        else {
            return response()->json(['message' => "Error Occured, while updating order support request."], 500);
        }
    }

    public function destroy($id)
    {
        $item = OrderSupport::where('id', $id)->delete();

        if ($item) {
            return response()->json(['data' => "Order Supoort Request Deleted."], 200);
        }
        else {
            return response()->json(['message' => "Error Occured, while deleting order support request."], 500);
        }
    }
}
