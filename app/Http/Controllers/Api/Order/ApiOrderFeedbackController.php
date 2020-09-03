<?php

namespace App\Http\Controllers\Api\Order;

use App\Events\Order\UserNotificationEvent;
use App\Http\Controllers\Controller;
use App\Http\Resources\Order\OrderFeedbackResource;
use App\Model\Order\Order;
use App\Model\Order\OrderFeedback;
use App\Notifications\UserNotification;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ApiOrderFeedbackController extends Controller
{
    public function addFeedback(Request $request, $orderID, $userIs) // userIs can be 'seller' | 'buyer' to define its seller feedback or buyer feedback
    {
        // return response()->json(['message' => $request->toArray(), 'orderID' => $orderID, 'userIs' => $userIs], 500);
        $orderfeedback = OrderFeedback::where('order_id', $orderID)->first();
        $order = Order::where('id', $orderID)->first();
        $date = Carbon::now();
        $sellerAverageRating = null;
        $sellerFeedbackDate = null;
        $buyerAverageRating = null;
        $buyerFeedbackDate = null;
        if ($orderfeedback) {
            if ($userIs == 'seller') {
                $order->update([
                    'seller_feedback_at' => $date
                ]);
                $sellerAverageRating = (($request->seller_rating_buyerCommunication + $request->seller_rating_buyerRecommended) / 2);
                $sellerFeedbackDate = $date;
            } else if ($userIs == 'buyer') {
                $order->update([
                    'buyer_feedback_at' => $date
                ]);
                $buyerAverageRating = (($request->buyer_rating_serviceAsDescribed + $request->buyer_rating_sellerCommunication + $request->buyer_rating_sellerRecommended) / 3);
                $buyerFeedbackDate = $date;
            } else {
                return response()->json(['message' => "Error Occured, while adding order feedback. userIs field in url is required and can only be equal to 'seller' or 'buyer'"], 500);
            }

            $result = $orderfeedback->update([
                'buyer_feedback_at' => !!$buyerFeedbackDate ? $buyerFeedbackDate : $orderfeedback->buyer_feedback_at,
                'buyer_feedback' => $request->has('buyer_feedback') ? $request->buyer_feedback : $orderfeedback->buyer_feedback,
                'buyer_rating_serviceAsDescribed' => $request->has('buyer_rating_serviceAsDescribed') ? $request->buyer_rating_serviceAsDescribed : $orderfeedback->buyer_rating_serviceAsDescribed,
                'buyer_rating_sellerCommunication' => $request->has('buyer_rating_sellerCommunication') ? $request->buyer_rating_sellerCommunication : $orderfeedback->buyer_rating_sellerCommunication,
                'buyer_rating_sellerRecommended' => $request->has('buyer_rating_sellerRecommended') ? $request->buyer_rating_sellerRecommended : $orderfeedback->buyer_rating_sellerRecommended,
                'buyer_rating' => !!$buyerAverageRating ? $buyerAverageRating : $orderfeedback->buyer_rating,

                'seller_feedback_at' => !!$sellerFeedbackDate ? $sellerFeedbackDate : $orderfeedback->seller_feedback_at,
                'seller_feedback' => $request->has('seller_feedback') ? $request->seller_feedback : $orderfeedback->seller_feedback,
                'seller_rating_buyerCommunication' => $request->has('seller_rating_buyerCommunication') ? $request->seller_rating_buyerCommunication : $orderfeedback->seller_rating_buyerCommunication,
                'seller_rating_buyerRecommended' => $request->has('seller_rating_buyerRecommended') ? $request->seller_rating_buyerRecommended : $orderfeedback->seller_rating_buyerRecommended,
                'seller_rating' => !!$sellerAverageRating ? $sellerAverageRating : $orderfeedback->seller_rating
            ]);
            if ($userIs == 'buyer') { // is buyer placed feedback notify seller
                try {
                    $result->buyer();
                    $orderFeedbackResource = new OrderFeedbackResource($result);
                    try {
                        $orderfeedback->seller()->notify(new UserNotification($orderFeedbackResource, 'order_feedback', 'seller'));
                        // broadcast(new UserNotificationEvent($orderFeedbackResource, 'order_feedback', 'seller')->toOthers();
                    } catch (\Throwable $th) {
                    }
                } catch (\Throwable $th) {
                }
            }
        } else {
            if ($userIs == 'seller') {
                $sellerAverageRating = (($request->seller_rating_buyerCommunication + $request->seller_rating_buyerRecommended) / 2);
                $sellerFeedbackDate = $date;
                $order->update([
                    'seller_feedback_at' => $sellerFeedbackDate
                ]);
            } else if ($userIs == 'buyer') {
                $buyerAverageRating = (($request->buyer_rating_serviceAsDescribed + $request->buyer_rating_sellerCommunication + $request->buyer_rating_sellerRecommended) / 3);
                $buyerFeedbackDate = $date;
                $order->update([
                    'buyer_feedback_at' => $buyerFeedbackDate
                ]);
            }

            $result = OrderFeedback::create([
                'order_id' => $order->id,
                'buyer_id' => $order->buyer_id,
                'seller_id' => $order->seller_id,
                'gig_id' => $order->gig_id,

                'buyer_feedback_at' => $buyerFeedbackDate,
                'buyer_satisfaction_level' => $request->has('buyer_satisfaction_level') ? $request->buyer_satisfaction_level : null,
                'buyer_feedback' => $request->has('buyer_feedback') ? $request->buyer_feedback : null,
                'buyer_rating_serviceAsDescribed' => $request->has('buyer_rating_serviceAsDescribed') ? $request->buyer_rating_serviceAsDescribed : null,
                'buyer_rating_sellerCommunication' => $request->has('buyer_rating_sellerCommunication') ? $request->buyer_rating_sellerCommunication : null,
                'buyer_rating_sellerRecommended' => $request->has('buyer_rating_sellerRecommended') ? $request->buyer_rating_sellerRecommended : null,
                'buyer_rating' => $buyerAverageRating,

                'seller_feedback_at' => $sellerFeedbackDate,
                'seller_feedback' => $request->has('seller_feedback') ? $request->seller_feedback : null,
                'seller_rating_buyerCommunication' => $request->has('seller_rating_buyerCommunication') ? $request->seller_rating_buyerCommunication : null,
                'seller_rating_buyerRecommended' => $request->has('seller_rating_buyerRecommended') ? $request->seller_rating_buyerRecommended : null,
                'seller_rating' => $sellerAverageRating
            ]);
        }
        if ($result) {
            return response()->json(['data' => "Order Feedback Added."], 200);
        } else {
            return response()->json(['message' => "Error Occured, while adding order feedback."], 500);
        }
    }
}
