<?php

namespace App\Http\Resources\Gig;

use Illuminate\Http\Resources\Json\JsonResource;

class GigParentCategoryResource extends JsonResource
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
            // 'is_visible' => $this->is_visible, // no need to show such things as these are just for backend login no place in frontend so don't fetch
            'icon_file_url' => $this->icon_file_url(),
            'image_file_url' => $this->image_file_url(),
            'banner_file_url' => $this->banner_file_url(),
            'video_file_url' => $this->video_file_url()
            // 'freelancers_increment' => $this->freelancers_increment,
            // 'is_popular' => $this->is_popular,
            // 'header_menu_item' => $this->header_menu_item,
        ];
    }
}
