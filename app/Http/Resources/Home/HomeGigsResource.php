<?php

namespace App\Http\Resources\Home;

use App\Http\Resources\Gig\GigParentCategoryResource;
use Illuminate\Http\Resources\Json\JsonResource;

class HomeGigsResource extends JsonResource
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
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'gig_type' => $this->gig_type,
            'hourly_rate' => $this->hourly_rate,
            'tags' => json_decode($this->tags, true),
            'status' => $this->status,
            'is_three_packages_mode_on' => !!$this->is_three_packages_mode_on,
            'is_extra_fast_delivery_on' => !!$this->is_extra_fast_delivery_on,
            'is_home_map_feature_item' => !!$this->is_home_map_feature_item,
            'is_home_expert_section_item' => !!$this->is_home_expert_section_item,
            'category' => new GigParentCategoryResource($this->category),
            'category_id' => $this->category ? $this->category->id : null,
            'user' => [
                'id' => $this->user->id,
                'username' => $this->user->username,
                'name' => $this->user->name,
                'profile_image' => !!$this->user->getProfileImg() ? $this->user->getProfileImg() : 'assets/img/placeholder.jpg',
                'member_since' => date('F Y', strtotime($this->user->created_at))
                // 'member_since' => "Member Since " . date('l F j, Y', strtotime($this->user->created_at))
            ]
        ];
    }
}
