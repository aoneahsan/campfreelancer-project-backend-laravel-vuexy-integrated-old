<?php

namespace App\Model\Order;

use App\Model\MainModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class OrderChat extends MainModel
{
    use SoftDeletes;
    protected $guarded = [];

    public function file_url()
    {
        if ($this->file_path) {
            return Storage::url($this->file_path);
        }
        else {
            return false;
        }
    }

    public function order()
    {
        return $this->belongsTo('App\Model\Order\Order', 'order_id', 'id');
    }

    public function sender()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    public function reciver()
    {
        return $this->hasOne('App\User', 'id', 'reciver_id');
    }
}
