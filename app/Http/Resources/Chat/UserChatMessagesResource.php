<?php

namespace App\Http\Resources\Chat;

use Illuminate\Http\Resources\Json\JsonResource;

class UserChatMessagesResource extends JsonResource
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
            'sender_id' => $this->user->id,
            'sender' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'profile_image' => $this->user->getProfileImg()
            ],
            'reciver_id' => $this->reciver->id,
            'message' => $this->message,
            'custom_offer_data' => !!$this->custom_offer_data ? json_decode($this->custom_offer_data) : false,
            'custom_offer_status' => !!$this->custom_offer_status ? $this->custom_offer_status : false,
            'file_url' => $this->file_url(),
            'type' => $this->type,
            'file_type' => $this->file_type,
            'is_reported' => !!$this->is_reported,
            'is_spammed' => !!$this->is_spammed,
            'created_at' => $this->created_at->diffForHumans(null, true, true, null, null)
        ];
    }
}
