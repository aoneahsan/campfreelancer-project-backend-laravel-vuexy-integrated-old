<?php

namespace App\Http\Resources\Order;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderFeedbackResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'seller_id' => $this->seller_id,
            'buyer_id' => $this->buyer_id,
            'order_id' => $this->order_id,
            'buyer_feedback_at' => $this->buyer_feedback_at,
            'buyer_feedback' => $this->buyer_feedback,
            'buyer_satisfaction_level' => $this->buyer_satisfaction_level,
            'buyer_rating_serviceAsDescribed' => $this->buyer_rating_serviceAsDescribed,
            'buyer_rating_sellerCommunication' => $this->buyer_rating_sellerCommunication,
            'buyer_rating_sellerRecommended' => $this->buyer_rating_sellerRecommended,
            'buyer_rating' => $this->buyer_rating,
            'seller_feedback_at' => $this->seller_feedback_at,
            'seller_feedback' => $this->seller_feedback,
            'seller_rating_buyerCommunication' => $this->seller_rating_buyerCommunication,
            'seller_rating_buyerRecommended' => $this->seller_rating_buyerRecommended,
            'seller_rating' => $this->seller_rating,
            'created_at' => $this->created_at->diffForHumans(null, true, true, null, null),
            'seller' => [
                'id' => $this->seller ? $this->seller->id : null,
                'name' => $this->seller ? $this->seller->name : null,
                'email' => $this->seller ? $this->seller->email : null,
                'username' => $this->seller ? $this->seller->username : null,
                'profile_image' => $this->seller ? $this->seller->getProfileImg() : null
            ],
            'buyer' => [
                'id' => $this->buyer ? $this->buyer->id : null,
                'name' => $this->buyer ? $this->buyer->name : null,
                'email' => $this->buyer ? $this->buyer->email : null,
                'username' => $this->buyer ? $this->buyer->username : null,
                'profile_image' => $this->buyer ? $this->buyer->getProfileImg() : null
            ]
        ];
    }
}
