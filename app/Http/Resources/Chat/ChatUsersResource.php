<?php

namespace App\Http\Resources\Chat;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ChatUsersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return [
        //     'id' => $this->id,
        //     'reciver' => [
        //         'id' => $this->reciver->id,
        //         'name' => $this->reciver->name,
        //         'profile_image' => $this->reciver->getProfileImg(),
        //         'email' => $this->reciver->email
        //     ],
        //     'is_favorite' => !!$this->is_favorite,
        //     'is_archived' => !!$this->is_archived,
        //     'is_spammed' => !!$this->is_spammed,
        //     'label_type' => !!$this->label_type,
        //     'latest_message' => $this->latest_message,
        //     'message_type' => $this->message_type,
        //     'message_sender_id' => $this->message_sender_id
        // ];
        return [
            'id' => $this->id,
            'reciver' => [
                'id' => $this->reciver->id,
                'username' => $this->reciver->username,
                'name' => $this->reciver->name,
                'profile_image' => !!$this->reciver->profile_img ? Storage::url($this->reciver->profile_img) : 'assets/img/placeholder.jpg',
                'email' => $this->reciver->email
            ],
            'is_favorite' => !!$this->is_favorite,
            'is_archived' => !!$this->is_archived,
            'is_spammed' => !!$this->is_spammed,
            'label_type' => $this->label_type,
            'latest_message' => $this->latest_message,
            'message_type' => $this->message_type,
            'message_sender_id' => $this->message_sender_id,
            'message_send_at' => !!$this->message_send_at ? Carbon::parse($this->message_send_at)->diffForHumans(null, true, true, null, null) : null,
            "unread_count" => $this->unread_count
        ];
    }
}
