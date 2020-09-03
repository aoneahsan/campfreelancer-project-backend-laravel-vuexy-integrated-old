<?php

namespace App\Model\Chat;

use App\Model\MainModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReportMessage extends MainModel
{
    use SoftDeletes;
    
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function messageSender()
    {
        return $this->hasOne('App\User', 'id', 'message_sender_id');
    }

    public function message()
    {
        return $this->hasOne('App\Modal\Chat\Message', 'id', 'message_id');
    }
}
