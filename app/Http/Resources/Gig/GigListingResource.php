<?php

namespace App\Http\Resources\Gig;

use Illuminate\Http\Resources\Json\JsonResource;

class GigListingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return
            [
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
                'category' => new GigParentCategoryResource($this->category),
                'category_id' => $this->category ? $this->category->id : null,
                'subcategory' => new GigParentCategoryResource($this->subcategory),
                'subcategory_id' => $this->subcategory ? $this->subcategory->id : null,
                'servicetype' => new GigCategoryServiceTypeResource($this->servicetype),
                'service_type_id' => $this->servicetype ? $this->servicetype->id : null,
                'gallery' => GigGalleryResource::collection($this->gallery),
                'packages' => GigPackageResource::collection($this->packages),
                'requirements' => GigRequirementResource::collection($this->requirements),
                'gig_rating' => !!$this->gig_ratings_count ? $this->gig_ratings_count : 0,
                'gig_sold_orders' => !!$this->gig_sold_orders_count ? $this->gig_sold_orders_count : 0,
                'user' => [
                    'id' => $this->user->id,
                    'username' => $this->user->username,
                    'name' => $this->user->name,
                    'profile_image' => !!$this->user->getProfileImg() ? $this->user->getProfileImg() : 'assets/img/placeholder.jpg',
                    'member_since' => date('F Y', strtotime($this->user->created_at))
                    // 'member_since' => "Member Since " . date('l F j, Y', strtotime($this->user->created_at))
                ]
            ];;
    }
}
