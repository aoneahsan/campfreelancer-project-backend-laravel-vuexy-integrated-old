<?php

namespace App\Http\Resources\JobRequest;

use App\Http\Resources\Gig\GigCategoryResource;
use Illuminate\Http\Resources\Json\JsonResource;

class JobRequestResource extends JsonResource
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
            'description' => $this->description,
            'file_url' => $this->file_url(),
            'time' => $this->time,
            'price' => $this->price,
            'is_hourly' => !!$this->is_hourly,
            'buyer_location' => $this->buyer_location,
            'request_type' => $this->request_type,
            'status' => $this->status,
            'time' => $this->time,
            'category' => [
                'id' => $this->category ? $this->category->id : null,
                'title' => $this->category ? $this->category->title : null
            ],
            'subcategory' => [
                'id' => $this->subcategory ? $this->subcategory->id : null,
                'title' => $this->subcategory ? $this->subcategory->title : null
            ],
            'service_type' => [
                'id' => $this->service_type ? $this->service_type->id : null,
                'title' => $this->service_type ? $this->service_type->title : null
            ],
            'category_id' => $this->category ? $this->category->id : null,
            'subcategory_id' => $this->subcategory ? $this->subcategory->id : null,
            'service_type_id' => $this->service_type ? $this->service_type->id : null,
            // 'offers' => JobOffersResource::collection($this->offers),
            'offers_count' => count($this->offers->toArray()),
            'created_at' => date('F j, Y', strtotime($this->created_at)),
            'buyer' => [
                'id' => $this->buyer ? $this->buyer->id : null,
                'name' => $this->buyer ? $this->buyer->name : null,
                'profile_image' => $this->buyer ? $this->buyer->getProfileImg() : null
            ]
        ];
    }
}
