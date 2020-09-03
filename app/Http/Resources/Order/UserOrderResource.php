<?php

namespace App\Http\Resources\Order;

use App\Http\Resources\Gig\GigGalleryResource;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class UserOrderResource extends JsonResource
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
            'buyer_id' => $this->buyer_id,
            'seller_id' => $this->seller_id,
            'gig_id' => $this->gig_id,
            'status' => $this->status,
            'order_number' => $this->order_number,
            'order_source' => $this->order_source,
            'order_title' => $this->order_title,
            'order_description' => $this->order_description,
            'order_time' => $this->order_time,
            'order_time_in_days' => $this->order_time_in_days,
            'orderTimeInSeconds' => Carbon::parse($this->order_time)->diffInSeconds(),
            'price' => $this->price,
            // 'seller_earning' => $this->seller_earning,
            'revisions' => $this->revisions,
            'order_rated_at' => !!$this->order_rated_at ? $this->order_rated_at : false,
            'buyer_feedback_at' => !!$this->buyer_feedback_at ? $this->buyer_feedback_at : false,
            'seller_feedback_at' => !!$this->seller_feedback_at ? $this->seller_feedback_at : false,
            'buyer_placed_tip_at' => !!$this->buyer_placed_tip_at ? $this->buyer_placed_tip_at : false,
            'ask_for_requirements' => !!$this->ask_for_requirements,
            'requirements_submited_at' => !!$this->requirements_submited_at ? $this->requirements_submited_at : false,
            'order_requirement_title' => $this->order_requirement_title,
            'order_requirement_description' => $this->order_requirement_description,
            'is_favorite' => !!$this->is_favorite,
            'is_late' => !!$this->is_late,
            'order_delivery_date' => !!$this->order_delivery_date ? $this->order_delivery_date : false,
            'order_delivered_at' => !!$this->order_delivered_at ? $this->order_delivered_at : false,
            'order_cancelled_at' => !!$this->order_cancelled_at ? $this->order_cancelled_at : false,
            'order_cancel_reason' => !!$this->order_cancel_reason ? $this->order_cancel_reason : false,
            'is_cleared' => !!$this->is_cleared,
            // 'order_completed_at' => !!$this->order_completed_at ? $this->order_completed_at : false,
            // 'amount_will_clear_at' => !!$this->amount_will_clear_at ? $this->amount_will_clear_at : false,
            // 'amount_cleared_at' => !!$this->amount_cleared_at ? $this->amount_cleared_at : false,
            // 'amount_added_in_seller_account_at' => !!$this->amount_added_in_seller_account_at ? $this->amount_added_in_seller_account_at : false,
            // 'checkout_response' => json_decode($this->checkout_response),
            // 'created_at' => $this->created_at
            'created_at' => $this->created_at->diffForHumans(null, true, true, null, null),
            'buyer' => [
                'id' => $this->buyer->id,
                'name' => $this->buyer->name,
                'email' => $this->buyer->email,
                'username' => $this->buyer->username,
                'profile_image' => $this->buyer->getProfileImg(),
            ],
            'seller' => [
                'id' => $this->seller->id,
                'name' => $this->seller->name,
                'email' => $this->seller->email,
                'username' => $this->seller->username,
                'profile_image' => $this->seller->getProfileImg(),
            ],
            'gig' => [
                'id' => $this->gig->id,
                'title' => $this->gig->title,
                'description' => $this->gig->description,
                'slug' => $this->gig->slug
            ],
            // 'gigGallery' => (count($this->gigGallery->toArray()) > 0) ? new GigGalleryResource($this->gigGallery[0]) : false,
            'deliveries' => $this->deliveries ? (count($this->deliveries) > 0 ? OrderDeliveryResource::collection($this->deliveries) : null) : null,
            'cancel_requests' => $this->cancelRequests ? (count($this->cancelRequests) > 0 ? OrderCancelRequestResource::collection($this->cancelRequests) : null) : null
        ];
    }
}
