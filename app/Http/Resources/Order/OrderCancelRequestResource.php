<?php

namespace App\Http\Resources\Order;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderCancelRequestResource extends JsonResource
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
            'user_id' => $this->user_id,
            'seller_id' => $this->seller_id,
            'buyer_id' => $this->buyer_id,
            'order_id' => $this->order_id,
            'order_number' => $this->order_number,
            'type' => $this->type,
            'reason' => $this->reason,
            'status' => $this->status,
            // 'file_path' => $this->file_path,
            // 'file_type' => $this->file_type,
            'response_message' => $this->response_message,
            'response_at' => $this->response_at,
            'created_at' => $this->created_at->diffForHumans(null, true, true, null, null),
            'request_user' => [
                'id' => $this->request_user ? $this->request_user->id : null,
                'name' => $this->request_user ? $this->request_user->name : null,
                'email' => $this->request_user ? $this->request_user->email : null,
                'username' => $this->request_user ? $this->request_user->username : null,
                'profile_image' => $this->request_user ? $this->request_user->getProfileImg() : null
            ],
            // 'seller' => [
            //     'id' => $this->seller ? $this->seller->id : null,
            //     'name' => $this->seller ? $this->seller->name : null,
            //     'email' => $this->seller ? $this->seller->email : null,
            //     'username' => $this->seller ? $this->seller->username : null,
            //     'profile_image' => $this->seller ? $this->seller->getProfileImg() : null
            // ],
            // 'buyer' => [
            //     'id' => $this->buyer ? $this->buyer->id : null,
            //     'name' => $this->buyer ? $this->buyer->name : null,
            //     'email' => $this->buyer ? $this->buyer->email : null,
            //     'username' => $this->buyer ? $this->buyer->username : null,
            //     'profile_image' => $this->buyer ? $this->buyer->getProfileImg() : null
            // ],
        ];
    }
}
