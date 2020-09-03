<?php

namespace App\Http\Resources\JobRequest;

use Illuminate\Http\Resources\Json\JsonResource;

class JobRequestCategoryFilterResource extends JsonResource
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
            'is_visible' => !!$this->is_visible
        ];
    }
}
