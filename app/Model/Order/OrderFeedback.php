<?php

namespace App\Model\Order;

use App\Model\MainModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderFeedback extends MainModel
{
    use SoftDeletes;
    protected $guarded = [];

    public function order()
    {
        return $this->belongsTo('App\Model\Order\Order', 'order_id', 'id');
    }

    public function seller()
    {
        return $this->hasOne('App\User', 'id', 'seller_id');
    }

    public function buyer()
    {
        return $this->hasOne('App\User', 'id', 'buyer_id');
    }

    public function scopeRatingEqual($query, $number)
    {
        return $query->where('buyer_rating', '>=', $number);
    }
}
