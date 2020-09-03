<?php

namespace App\Eloquent\Traits\User;

trait OrderRatingTrait
{

    public function orderRatingAsBuyer()
    {
        return $this->hasMany('App\Model\Order\OrderFeedback', 'buyer_id', 'id')->where('seller_feedback_at', '!=', null);
    }

    public function orderRatingAsSeller()
    {
        return $this->hasMany('App\Model\Order\OrderFeedback', 'seller_id', 'id')->where('buyer_feedback_at', '!=', null);
    }

    public function pendingFeedbackAsSeller()
    {
        return $this->hasMany('App\Model\Order\OrderFeedback', 'seller_id', 'id')->where('buyer_feedback_at', '=', null);
    }

    public function fiveStartRatingAsSeller()
    {
        return $this->orderRatingAsSeller()->where('buyer_rating', '>=', 4.5);
    }

    public function fourStartRatingAsSeller()
    {
        return $this->orderRatingAsSeller()->where('buyer_rating', '>=', 3.5)->where('buyer_rating', '<=', 4.4);
    }

    public function threeStartRatingAsSeller()
    {
        return $this->orderRatingAsSeller()->where('buyer_rating', '>=', 2.5)->where('buyer_rating', '<=', 3.4);
    }

    public function twoStartRatingAsSeller()
    {
        return $this->orderRatingAsSeller()->where('buyer_rating', '>=', 1.5)->where('buyer_rating', '<=', 2.4);
    }

    public function oneStartRatingAsSeller()
    {
        return $this->orderRatingAsSeller()->where('buyer_rating', '<=', 1.4);
    }

    public function communicationRatingAsSeller()
    {
        return $this->orderRatingAsSeller();
    }

    public function serviceAsDescribedRatingAsSeller()
    {
        return $this->orderRatingAsSeller();
    }

    public function recommendRatingAsSeller()
    {
        return $this->orderRatingAsSeller();
    }
}
