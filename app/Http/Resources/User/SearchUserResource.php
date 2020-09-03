<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class SearchUserResource extends JsonResource
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
            'username' => $this->username,
            'name' => $this->name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'profile_img' => $this->getProfileImg(),
            'city' => $this->details->city ? $this->details->city : null,
            'country' => $this->details->country ? $this->details->country : null
        ];
    }
}
