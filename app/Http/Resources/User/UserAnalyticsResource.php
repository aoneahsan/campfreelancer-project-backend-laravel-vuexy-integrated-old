<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class UserAnalyticsResource extends JsonResource
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
            'user_id' => $this->id,
            'netIncome' => !!$this->net_income_count ? $this->net_income_count : 0,
            'earned_in_this_month' => !!$this->completed_orders_as_seller_specific_range_count ? $this->completed_orders_as_seller_specific_range_count : 0,
            'seller_orders' => $this->ordersAsSeller, // this contains orders with status 'active', 'completed', 'cancelled' // with orderBy with created at
            'completed_orders_count' => !!$this->completed_orders_as_seller_count ? $this->completed_orders_as_seller_count : 0,
            'avarage_selling_price' => !!$this->avarage_selling_price_count ? $this->avarage_selling_price_count : 0,
            'ratings' => [
                'five_star_rating' => !!$this->five_start_rating_as_seller ? $this->five_start_rating_as_seller : 0,
                'four_star_rating' => !!$this->four_start_rating_as_seller ? $this->four_start_rating_as_seller : 0,
                'three_star_rating' => !!$this->three_start_rating_as_seller ? $this->three_start_rating_as_seller : 0,
                'two_star_rating' => !!$this->two_start_rating_as_seller ? $this->two_start_rating_as_seller : 0,
                'one_star_rating' => !!$this->one_start_rating_as_seller ? $this->one_start_rating_as_seller : 0,
                'total_completed_orders' => !!$this->cancelledOrdersAsSeller ? count($this->cancelledOrdersAsSeller) : 0,
                'orders_with_no_feedback' => !!$this->pending_feedback_as_seller_count ? $this->pending_feedback_as_seller_count : 0, // calculate percentage for orders with no feedback
                'rated_orders_percentage' => (100 - (($this->pending_feedback_as_seller_count * 100)/$this->completed_orders_as_seller_count))
            ]
        ];
    }
}
