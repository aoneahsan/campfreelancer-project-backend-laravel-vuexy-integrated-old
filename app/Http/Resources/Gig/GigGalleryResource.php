<?php

namespace App\Http\Resources\Gig;

use Illuminate\Http\Resources\Json\JsonResource;

class GigGalleryResource extends JsonResource
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
            'file_type' => $this->file_type,
            'file_name' => $this->file_name,
            'file_number' => $this->file_number,
            'file_url' => $this->file_url()
        ];
    }
}
