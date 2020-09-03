<?php

namespace App\Model\Order;

use App\Model\MainModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends MainModel
{
    use SoftDeletes;

    protected $guarded = [];

    public function buyer()
    {
        return $this->belongsTo('App\User', 'buyer_id', 'id');
    }

    public function seller()
    {
        return $this->hasOne('App\User', 'id', 'seller_id');
    }

    public function gig()
    {
        return $this->hasOne('App\User', 'id', 'gig_id');
    }

    // public function gigGallery()
    // {
    //     return $this->hasMany('App\Model\Gig\GigGallery', 'gig_id', 'id');
    // }

    public function orderChat()
    {
        return $this->hasMany('App\Model\Order\OrderChat', 'order_id', 'id');
    }

    public function orderFeedback()
    {
        return $this->hasMany('App\Model\Order\OrderFeedback', 'order_id', 'id');
    }

    public function orderSupport()
    {
        return $this->hasMany('App\Model\Order\OrderSupport', 'order_id', 'id');
    }

    public function orderTip()
    {
        return $this->hasMany('App\Model\Order\OrderTip', 'order_id', 'id');
    }

    public function deliveries()
    {
        return $this->hasMany('App\Model\Order\OrderDelivery', 'order_id', 'id');
    }

    public function cancelRequests()
    {
        return $this->hasMany('App\Model\Order\OrderCancelRequest', 'order_id', 'id');
    }
}
