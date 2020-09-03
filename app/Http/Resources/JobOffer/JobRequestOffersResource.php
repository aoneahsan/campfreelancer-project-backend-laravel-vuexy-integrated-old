<?php

namespace App\Http\Resources\JobOffer;

use App\Http\Resources\Gig\GigRequirementResource;
use Illuminate\Http\Resources\Json\JsonResource;

class JobRequestOffersResource extends JsonResource
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
                'file_url' => $this->gig ? ($this->gig->gallery ? ((count($this->gig->gallery) > 0) ? $this->gig->gallery[0]->file_url() : null) : null) : null,
                'requirements' => $this->gig->requirements ? ((count($this->gig->requirements) > 0) ? (new GigRequirementResource($this->gig->requirements[0])) : null ) : null
            ],
            'seller' => [
                'id' => $this->seller->id,
                'username' => $this->seller->username,
                'name' => $this->seller->name,
                'profile_image' => $this->seller ? $this->seller->getProfileImg() : null
            ]
        ];
    }
}
