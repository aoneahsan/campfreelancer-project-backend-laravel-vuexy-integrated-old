<?php

namespace App\Http\Resources\JobRequest;

use Illuminate\Http\Resources\Json\JsonResource;

class SaveJobRequestsResource extends JsonResource
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
            'requestdetails' => [
                'id' => $this->requestdetails->id,
                'description' => $this->requestdetails->description,
                'file_url' => $this->requestdetails->file_url(),
                'time' => $this->requestdetails->time,
                'price' => $this->requestdetails->price,
                'is_hourly' => !!$this->requestdetails->is_hourly,
                'buyer_location' => $this->requestdetails->buyer_location,
                'request_type' => $this->requestdetails->request_type,
                'status' => $this->requestdetails->status,
                'category' => [
                    'id' => $this->requestdetails->category ? $this->requestdetails->category->id : null,
                    'title' => $this->requestdetails->category ? $this->requestdetails->category->title : null
                ],
                'subcategory' => [
                    'id' => $this->requestdetails->subcategory ? $this->requestdetails->subcategory->id : null,
                    'title' => $this->requestdetails->subcategory ? $this->requestdetails->subcategory->title : null
                ],
                'service_type' => [
                    'id' => $this->requestdetails->service_type ? $this->requestdetails->service_type->id : null,
                    'title' => $this->requestdetails->service_type ? $this->requestdetails->service_type->title : null
                ],
                'category_id' => $this->requestdetails->category ? $this->requestdetails->category->id : null,
                'subcategory_id' => $this->requestdetails->subcategory ? $this->requestdetails->subcategory->id : null,
                'service_type_id' => $this->requestdetails->service_type ? $this->requestdetails->service_type->id : null,
                'offers_count' => count($this->requestdetails->offers),
                'created_at' => date('F j, Y', strtotime($this->created_at))
            ],
            'buyerdetails' => [
                'id' => $this->buyerdetails->id,
                'name' => $this->buyerdetails->name,
                'email' => $this->buyerdetails->email,
                'phone_number' => $this->buyerdetails->phone_number,
                'profile_img' => $this->buyerdetails->getProfileImg(),
                'city' => $this->buyerdetails->details->city ? $this->buyerdetails->details->city : null,
                'country' => $this->buyerdetails->details->country ? $this->buyerdetails->details->country : null
            ]
        ];
    }
}
