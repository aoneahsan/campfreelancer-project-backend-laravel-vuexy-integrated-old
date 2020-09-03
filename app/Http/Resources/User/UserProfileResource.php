<?php

namespace App\Http\Resources\User;

use App\Http\Resources\Order\OrderFeedbackResource;
use App\Http\Resources\Shared\MembershipPlanResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileResource extends JsonResource
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
            'username' => $this->username,
            'name' => $this->name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'country_code' => $this->country_code,
            'country_code_text' => $this->country_code_text,
            'profile_image' => $this->getProfileImg(),
            'role' => $this->role,
            'member_since' => date('F j, Y', strtotime($this->created_at)),
            'is_buyer' => !!$this->is_buyer,
            'is_2fa_verified' => !!$this->is_2fa_verified,
            'is_2fa_enabled' => !!$this->is_2fa_enabled,
            'profile_publicly_visible' => $this->profile_publicly_visible,
            'membership_plan_id' => $this->membership_plan_id,
            // 'tokken' => $this->getTokken(),
            'account' => [
                'balance' => $this->account->balance ? $this->account->balance : 0,
                'accept_custom_offers' => !!$this->account->accept_custom_offers
            ],
            'details' => [
                'user_intro' => $this->details->user_intro ? $this->details->user_intro : null,
                'user_description' => $this->details->user_description ? $this->details->user_description : null,
                'user_average_response_time' => $this->details->user_average_response_time ? $this->details->user_average_response_time : null,
                'user_languages' => $this->details->user_languages ? json_decode($this->details->user_languages) : null,
                'user_skills' => $this->details->user_skills ? json_decode($this->details->user_skills) : null,
                'user_education' => $this->details->user_education ? $this->details->user_education : null,
                'cnic' => $this->details->cnic ? $this->details->cnic : null,
                'cnic' => $this->details->cnic ? $this->details->cnic : null,
                'location' => $this->details->location ? $this->details->location : null,
                'city' => $this->details->city ? $this->details->city : null,
                'country' => $this->details->country ? $this->details->country : null,
                'cnic_front' => $this->details->cnic_front ? $this->details->cnic_front : null,
                'cnic_back' => $this->details->cnic_back ? $this->details->cnic_back : null,
                'facebook_link' => $this->details->facebook_link ? $this->details->facebook_link : null,
                'linkedin_link' => $this->details->linkedin_link ? $this->details->linkedin_link : null,
                'twitter_link' => $this->details->twitter_link ? $this->details->twitter_link : null,
                'github_link' => $this->details->github_link ? $this->details->github_link : null
            ],
            'rating' => [
                'all_feedbacks' => !!$this->orderRatingAsSeller ? OrderFeedbackResource::collection($this->orderRatingAsSeller) : 0,
                'overall_rating' => !!$this->order_rating_as_seller_count ? $this->order_rating_as_seller_count : 0,
                'communication_rating' => !!$this->communication_rating_as_seller_count ? $this->communication_rating_as_seller_count : 0,
                'service_rating' => !!$this->service_as_described_rating_as_seller_count ? $this->service_as_described_rating_as_seller_count : 0,
                'recommend_rating' => !!$this->recommend_rating_as_seller_count ? $this->recommend_rating_as_seller_count : 0
            ],
            'payment_methods' => UserPaymentMethodResource::collection($this->paymentMethods),
            'membership_plan_details' => new MembershipPlanResource($this->membershipplandetails)
        ];
    }
}
