<?php

namespace App\Model\Order;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class OrderDelivery extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    public function file_url()
    {
        if ($this->file_path) {
            return Storage::url($this->file_path);
        } else {
            return false;
        }
    }

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
}
