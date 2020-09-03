<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserPaymentMethodResource;
use App\Model\User\UserPaymentMethod;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ApiUserPaymentMethodController extends Controller
{
    public function index(Request $request)
    {
        $items = UserPaymentMethod::where('user_id', $request->user()->id)->get();
        return response()->json(['data' => UserPaymentMethodResource::collection($items)], 200);
    }

    public function store(Request $request)
    {
        $date = Carbon::now();
        $item = UserPaymentMethod::create([
            'user_id' => $request->user()->id,
            'payment_method_company' => $request->has('payment_method_company') ? $request->payment_method_company : null,
            'payment_method_name' => $request->has('payment_method_name') ? $request->payment_method_name : null,
            'payment_method_emailID' => $request->has('payment_method_emailID') ? $request->payment_method_emailID : null,
            'payment_method_username' => $request->has('payment_method_username') ? $request->payment_method_username : null,
            'payment_method_accountNumber' => $request->has('payment_method_accountNumber') ? $request->payment_method_accountNumber : null,
            'is_active' => $request->has('is_active') ? $request->is_active : null,
            'payment_method_added_at' => $date
        ]);
        if ($item) {
            return response()->json(['data' => new UserPaymentMethodResource($item)], 200);
        } else {
            return response()->json(['message' => "Error Occured while adding payment method, try again."], 500);
        }
        
    }

    public function show($id)
    {
        $item = UserPaymentMethod::where('id', $id)->first();
        if ($item) {
            return response()->json(['data' => new UserPaymentMethodResource($item)], 200);
        } else {
            return response()->json(['message' => "No payment method Found!"], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $date = Carbon::now();
        $item = UserPaymentMethod::where('id', $id)->first();
        if ($item) {
            $result = $item->update([
                'payment_method_company' => $request->has('payment_method_company') ? $request->payment_method_company : $item->payment_method_company,
                'payment_method_name' => $request->has('payment_method_name') ? $request->payment_method_name : $item->payment_method_name,
                'payment_method_emailID' => $request->has('payment_method_emailID') ? $request->payment_method_emailID : $item->payment_method_emailID,
                'payment_method_username' => $request->has('payment_method_username') ? $request->payment_method_username : $item->payment_method_username,
                'payment_method_accountNumber' => $request->has('payment_method_accountNumber') ? $request->payment_method_accountNumber : $item->payment_method_accountNumber,
                'is_active' => $request->has('is_active') ? $request->is_active : $item->is_active,
            ]);
            if ($result) {
                $updatedItem = UserPaymentMethod::where('id', $id)->first();
                return response()->json(['data' => new UserPaymentMethodResource($updatedItem)], 200);
            } else {
                return response()->json(['message' => "Error Occured while updating payment method, try again."], 500);
            }
        } else {
            return response()->json(['message' => "No payment method Found!"], 500);
        }
    }

    public function destroy($id)
    {
        $item = UserPaymentMethod::where('id', $id)->delete();
        if ($item) {
            return response()->json(['data' => "Payment Method Removed successfully!"], 200);
        } else {
            return response()->json(['message' => "No payment method Found!"], 500);
        }
    }
}
