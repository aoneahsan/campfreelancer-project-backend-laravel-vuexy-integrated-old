<?php

namespace App\Http\Resources\Gig;

use Illuminate\Http\Resources\Json\JsonResource;

class GigCategoryResource extends JsonResource
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
            // 'is_visible' => $this->is_visible,
            'icon_file_url' => $this->icon_file_url(),
            'image_file_url' => $this->image_file_url(),
            'banner_file_url' => $this->banner_file_url(),
            'video_file_url' => $this->video_file_url(),
            'freelancers_increment' => $this->freelancers_increment,
            // 'is_popular' => $this->is_popular,
            // 'header_menu_item' => $this->header_menu_item,
            'subcategories' => GigCategoryResource::collection($this->subcategories)
        ];
    }
}
