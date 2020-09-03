<?php

namespace App\Http\Resources\Gig;

use Illuminate\Http\Resources\Json\JsonResource;

class GigPackageResource extends JsonResource
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
            'description' => $this->description,
            'time' => $this->time,
            'revisions' => $this->revisions,
            'price' => $this->price,
            'is_hourly' => !!$this->is_hourly,
            'extra_fast_delivery_enabled' => !!$this->extra_fast_delivery_enabled,
            'extra_fast_delivery_time' => $this->extra_fast_delivery_time,
            'extra_fast_delivery_price' => $this->extra_fast_delivery_price,
            'sort_order' => $this->sort_order,
            'is_visible' => $this->is_visible
        ];
    }
}
