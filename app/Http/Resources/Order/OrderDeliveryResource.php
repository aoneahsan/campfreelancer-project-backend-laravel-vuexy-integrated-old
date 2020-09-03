<?php

namespace App\Http\Resources\Order;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderDeliveryResource extends JsonResource
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
            'order_id' => $this->order_id,
            'buyer_id' => $this->buyer_id,
            'seller_id' => $this->seller_id,
            'seller' => [
                'id' => $this->seller->id,
                'username' => $this->seller->username,
                'name' => $this->seller->name,
                'email' => $this->seller->email,
                'profile_image' => $this->seller->getProfileImg()
            ],
            'buyer' => [
                'id' => $this->buyer->id,
                'username' => $this->buyer->username,
                'name' => $this->buyer->name,
                'email' => $this->buyer->email,
                'profile_image' => $this->buyer->getProfileImg()
            ],
            'order' => [
                'id' => $this->order->id,
                'order_number' => $this->order->order_number,
                'status' => $this->order->status,
                'order_delivered_at' => $this->order->order_delivered_at,
                'order_cancelled_at' => $this->order->order_cancelled_at
            ],
            'message' => $this->message,
            'file_url' => $this->file_url(),
            'status' => $this->status,
            'file_type' => $this->file_type,
            // 'revision' => !!$this->revision ? json_decode($this->revision) : null,
            'revision' => $this->revision,
            'updated_at' => $this->updated_at,
            'delivery_placed_at' => $this->delivery_placed_at,
            'created_at' => $this->created_at
        ];
    }
}
