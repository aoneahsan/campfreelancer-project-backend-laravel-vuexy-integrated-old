<?php

namespace App\Http\Resources\JobOffer;

use Illuminate\Http\Resources\Json\JsonResource;

class JobOfferResource extends JsonResource
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
            'seller_id' => $this->user_id,
            'buyer_id' => $this->buyer_id,
            'job_request_id' => $this->job_request_id,
            'gig_id' => $this->gig_id,
            'description' => $this->description,
            'price' => $this->price,
            'time' => $this->time,
            'no_of_revisions' => $this->no_of_revisions,
            'ask_for_gig_requirements' => !!$this->ask_for_gig_requirements,
            'status' => !!$this->status,
            'gig' => [
                'id' => $this->gig->id,
                'title' => $this->gig->title,
                'file_url' => $this->gig ? ($this->gig->gallery ? ((count($this->gig->gallery) > 0) ? $this->gig->gallery[0]->file_url() : null) : null) : null
            ],
            'buyer' => [
                'id' => $this->buyer->id,
                'username' => $this->buyer->username,
                'name' => $this->buyer->name,
                'profile_image' => $this->buyer ? $this->buyer->getProfileImg() : null
            ],
            'jobrequest' => [
                'id' => $this->jobrequest->id,
                'description' => $this->jobrequest->description,
                'file_url' => $this->jobrequest ? $this->jobrequest->file_url() : null,
                'time' => $this->jobrequest->time,
                'price' => $this->jobrequest->price,
                'is_hourly' => !!$this->jobrequest->is_hourly,
            ]
        ];
    }
}
