<?php

namespace App\Model\Order;

use App\Model\MainModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderTip extends MainModel
{
    use SoftDeletes;
    protected $guarded = [];

    public function order()
    {
        return $this->belongsTo('App\Model\Order\Order', 'order_id', 'id');
    }

    public function seller()
    {
        if ($this->seller_id) {
            return $this->hasOne('App\User', 'id', 'seller_id');
        } else {
            return false;
        }
    }

    public function buyer()
    {
        if ($this->buyer_id) {
            return $this->hasOne('App\User', 'id', 'buyer_id');
        } else {
            return false;
        }
    }
}
