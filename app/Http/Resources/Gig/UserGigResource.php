<?php

namespace App\Http\Resources\Gig;

use Illuminate\Http\Resources\Json\JsonResource;

class UserGigResource extends JsonResource
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
            'category' => new GigParentCategoryResource($this->category),
            'category_id' => $this->category ? $this->category->id : null,
            'subcategory' => new GigParentCategoryResource($this->subcategory),
            'subcategory_id' => $this->subcategory ? $this->subcategory->id : null,
            'servicetype' => new GigCategoryServiceTypeResource($this->servicetype),
            'service_type_id' => $this->servicetype ? $this->servicetype->id : null,
            'gallery' => GigGalleryResource::collection($this->gallery),
            'packages' => GigPackageResource::collection($this->packages),
            'requirements' => GigRequirementResource::collection($this->requirements)
        ];
    }
}
