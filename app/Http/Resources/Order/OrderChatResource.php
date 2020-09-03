<?php

namespace App\Http\Resources\Order;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderChatResource extends JsonResource
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
            // 'user_id' => $this->user_id,
            'reciver_id' => $this->reciver_id,
            'sender_id' => $this->user_id,
            // 'reciver' => [
            //     'id' => $this->reciver->id,
            //     'name' => $this->reciver->name,
            //     'email' => $this->reciver->email,
            //     'username' => $this->reciver->username,
            //     'profile_image' => $this->reciver->getProfileImg()
            // ],
            'sender' => [
                'id' => $this->sender->id,
                'name' => $this->sender->name,
                'email' => $this->sender->email,
                'username' => $this->sender->username,
                'profile_image' => $this->sender->getProfileImg()
            ],
            'order_id' => $this->order_id,
            'message' => $this->message,
            'type' => $this->type,
            'file_type' => $this->file_type,
            'is_reported' => $this->is_reported,
            'is_spammed' => $this->is_spammed,
            'file_url' => $this->file_url(),
            'created_at' => $this->created_at->diffForHumans(null, true, true, null, null)
        ];
    }
}
