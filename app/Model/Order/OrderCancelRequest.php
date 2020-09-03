<?php

namespace App\Model\Order;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderCancelRequest extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function request_user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
