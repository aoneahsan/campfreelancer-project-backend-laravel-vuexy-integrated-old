<?php

namespace App\Http\Resources\Gig;

use App\Http\Resources\Order\OrderFeedbackResource;
use Illuminate\Http\Resources\Json\JsonResource;

class GigPreviewResource extends JsonResource
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
            'gig_type' => $this->gig_type,
            'hourly_rate' => $this->hourly_rate,
            'tags' => json_decode($this->tags, true),
            'status' => $this->status,
            'is_three_packages_mode_on' => !!$this->is_three_packages_mode_on,
            'is_extra_fast_delivery_on' => !!$this->is_extra_fast_delivery_on,
            'category' => new GigParentCategoryResource($this->category),
            'subcategory' => new GigParentCategoryResource($this->subcategory),
            'servicetype' => new GigCategoryServiceTypeResource($this->servicetype),
            'gallery' => GigGalleryResource::collection($this->gallery),
            'packages' => GigPackageResource::collection($this->packages),
            'requirements' => GigRequirementResource::collection($this->requirements),
            'user' => [
                'id' => $this->user->id,
                'username' => $this->user->username,
                'name' => $this->user->name,
                'profile_image' => !!$this->user->getProfileImg() ? $this->user->getProfileImg() : 'assets/img/placeholder.jpg',
                'member_since' => date('F Y', strtotime($this->user->created_at))
                // 'member_since' => "Member Since " . date('l F j, Y', strtotime($this->user->created_at))
            ],
            'user_details' => [
                'user_intro' => $this->userDetails->user_intro ? $this->userDetails->user_intro : null,
                'user_description' => $this->userDetails->user_description ? $this->userDetails->user_description : null,
                'user_average_response_time' => $this->userDetails->user_average_response_time ? $this->userDetails->user_average_response_time : null,
                'user_languages' => $this->userDetails->user_languages ? json_decode($this->userDetails->user_languages) : null,
                'user_skills' => $this->userDetails->user_skills ? json_decode($this->userDetails->user_skills) : null,
                'user_education' => $this->userDetails->user_education ? json_decode($this->userDetails->user_education) : null,
                'cnic' => $this->userDetails->cnic ? $this->userDetails->cnic : null,
                'cnic' => $this->userDetails->cnic ? $this->userDetails->cnic : null,
                'location' => $this->userDetails->location ? $this->userDetails->location : null,
                'city' => $this->userDetails->city ? $this->userDetails->city : null,
                'country' => $this->userDetails->country ? $this->userDetails->country : null,
                'cnic_front' => $this->userDetails->cnic_front ? $this->userDetails->cnic_front : null,
                'cnic_back' => $this->userDetails->cnic_back ? $this->userDetails->cnic_back : null,
                'facebook_link' => $this->userDetails->facebook_link ? $this->userDetails->facebook_link : null,
                'linkedin_link' => $this->userDetails->linkedin_link ? $this->userDetails->linkedin_link : null,
                'twitter_link' => $this->userDetails->twitter_link ? $this->userDetails->twitter_link : null,
                'github_link' => $this->userDetails->github_link ? $this->userDetails->github_link : null
            ],
            'gig_ratings' => [
                'overall_rating' => $this->gig_ratings_count ? $this->gig_ratings_count : 0,
                'reviews' => $this->gigRatings ? OrderFeedbackResource::collection($this->gigRatings) : null,
                'gig_communication_rating_count' => $this->gig_communication_rating_count ? $this->gig_communication_rating_count : 0,
                'gig_service_rating_count' => $this->gig_service_rating_count ? $this->gig_service_rating_count : 0,
                'gig_recommend_rating_count' => $this->gig_recommend_rating_count ? $this->gig_recommend_rating_count : 0,
                'gig_five_star_ratings_count' => $this->gig_five_star_ratings_count ? $this->gig_five_star_ratings_count : 0,
                'gig_four_star_ratings_count' => $this->gig_four_star_ratings_count ? $this->gig_four_star_ratings_count : 0,
                'gig_three_star_ratings_count' => $this->gig_three_star_ratings_count ? $this->gig_three_star_ratings_count : 0,
                'gig_two_star_ratings_count' => $this->gig_two_star_ratings_count ? $this->gig_two_star_ratings_count : 0,
                'gig_one_star_ratings_count' => $this->gig_one_star_ratings_count ? $this->gig_one_star_ratings_count : 0
            ]
        ];
    }
}
