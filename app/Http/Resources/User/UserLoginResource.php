<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class UserLoginResource extends JsonResource
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
            'country_code' => $this->country_code,
            'profile_image' => $this->getProfileImg(),
            'role' => $this->role,
            'member_since' => "Member Since " . date('l F j, Y', strtotime($this->created_at)),
            'is_buyer' => !!$this->is_buyer,
            'is_2fa_enabled' => !!$this->is_2fa_enabled,
            'is_2fa_verified' => !!$this->is_2fa_verified,
            'profile_publicly_visible' => $this->profile_publicly_visible,
            'tokken' => $this->getTokken()
        ];
    }
}
