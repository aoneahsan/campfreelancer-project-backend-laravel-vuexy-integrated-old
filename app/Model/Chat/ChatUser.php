<?php

namespace App\Model\Chat;

use App\Model\MainModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChatUser extends MainModel
{
    use SoftDeletes;
    
    protected $guarded = [];

    public function sender()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    public function reciver()
    {
        return $this->hasOne('App\User', 'id', 'reciver_id');
    }
}
