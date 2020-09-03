<?php

namespace App\Http\Resources\Shared;

use Illuminate\Http\Resources\Json\JsonResource;

class MembershipPlanResource extends JsonResource
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
            'plan_number' => $this->plan_number,
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->price,
            'can_offer_requests' => !!$this->can_offer_requests,
            'bids_allowed' => $this->bids_allowed,
            'commission_per_order' => $this->commission_per_order,
            'order_placing_service_charges' => $this->order_placing_service_charges,
            'can_post_request' => !!$this->can_post_request,
            'post_premium_requests' => !!$this->post_premium_requests,
            'show_primium_request' => !!$this->show_primium_request,
            'can_add_gigs' => !!$this->can_add_gigs,
            'plan_type' => $this->plan_type
        ];
    }
}
