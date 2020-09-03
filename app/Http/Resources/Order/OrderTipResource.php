<?php

namespace App\Http\Resources\Order;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderTipResource extends JsonResource
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
            ],
            'tip_amount' => $this->tip_amount,
            'reason' => $this->reason,
            'time' => $this->time,
            'created_at' => $this->created_at->diffForHumans(null, true, true, null, null)
        ];
    }
}
