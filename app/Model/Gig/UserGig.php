<?php

namespace App\Model\Gig;

use App\Model\MainModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserGig extends MainModel
{

    use SoftDeletes;

    // protected $fillable = [
    //     'user_id', 'category_id', 'subcategory_id', 'service_type_id', 'title', 'slug', 'description', 'hourly_rate', 'tags', 'status', 'gig_type'
    // ];

    protected $guarded = [];

    // protected $casts = [
    //     'tags' => 'array'
    // ];

    // public function creating($model)
    // {
    //     return true;
    // }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function userDetails()
    {
        return $this->hasOne('App\Model\User\UserDetails', 'user_id', 'user_id');
    }

    public function category()
    {
        return $this->hasOne('App\Model\Gig\GigCategory', 'id', 'category_id');
    }

    public function subcategory()
    {
        return $this->hasOne('App\Model\Gig\GigCategory', 'id', 'subcategory_id');
    }

    public function servicetype()
    {
        return $this->hasOne('App\Model\Gig\GigServiceType', 'id', 'service_type_id');
    }

    public function gallery()
    {
        return $this->hasMany('App\Model\Gig\GigGallery', 'gig_id', 'id');
    }

    public function packages()
    {
        return $this->hasMany('App\Model\Gig\GigPackage', 'gig_id', 'id');
    }

    public function requirements()
    {
        return $this->hasMany('App\Model\Gig\GigRequirement', 'gig_id', 'id');
    }

    public function analytics()
    {
        return $this->hasMany('App\Model\Gig\GigAnalytics', 'gig_id', 'id');
    }

    public function gigSoldOrders()
    {
        return $this->hasMany('App\Model\Order\Order', 'gig_id', 'id')->where('status', 'completed');
    }

    public function gigRatings()
    {
        return $this->hasMany('App\Model\Order\OrderFeedback', 'gig_id', 'id')->where('buyer_feedback_at', '!=', null)->orderBy('buyer_rating');
    }

    public function gigFiveStarRatings()
    {
        return $this->hasMany('App\Model\Order\OrderFeedback', 'gig_id', 'id')->where('buyer_feedback_at', '!=', null)->where('buyer_rating', '>', 4);
    }

    public function gigFourStarRatings()
    {
        return $this->hasMany('App\Model\Order\OrderFeedback', 'gig_id', 'id')->where('buyer_feedback_at', '!=', null)->where('buyer_rating', '>', 3)->where('buyer_rating', '<', 4.1);
    }

    public function gigThreeStarRatings()
    {
        return $this->hasMany('App\Model\Order\OrderFeedback', 'gig_id', 'id')->where('buyer_feedback_at', '!=', null)->where('buyer_rating', '>', 2)->where('buyer_rating', '<', 3.1);
    }

    public function gigTwoStarRatings()
    {
        return $this->hasMany('App\Model\Order\OrderFeedback', 'gig_id', 'id')->where('buyer_feedback_at', '!=', null)->where('buyer_rating', '>', 1)->where('buyer_rating', '<', 2.1);
    }

    public function gigOneStarRatings()
    {
        return $this->hasMany('App\Model\Order\OrderFeedback', 'gig_id', 'id')->where('buyer_feedback_at', '!=', null)->where('buyer_rating', '<', 1.1);
    }

    public function gigCommunicationRating()
    {
        return $this->hasMany('App\Model\Order\OrderFeedback', 'gig_id', 'id')->where('buyer_feedback_at', '!=', null);
    }

    public function gigServiceRating()
    {
        return $this->hasMany('App\Model\Order\OrderFeedback', 'gig_id', 'id')->where('buyer_feedback_at', '!=', null);
    }

    public function gigRecommendRating()
    {
        return $this->hasMany('App\Model\Order\OrderFeedback', 'gig_id', 'id')->where('buyer_feedback_at', '!=', null);
    }
}
