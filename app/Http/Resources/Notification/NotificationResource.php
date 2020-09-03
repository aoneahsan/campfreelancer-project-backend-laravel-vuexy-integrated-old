<?php

namespace App\Http\Resources\Notification;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
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
            // 'title' => isset($this->data['title']) ? $this->data['title'] : null,
            'data' => $this->data,
            // 'image_url' => isset($this->data['image_url']) ? $this->data['image_url'] : null,
            'type' => $this->type,
            'read_at' => $this->read_at,
            'created_at' => $this->created_at->diffForHumans(null, true, true, null, null)
        ];
    }
}
