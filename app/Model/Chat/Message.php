<?php

namespace App\Model\Chat;

use App\Model\MainModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Message extends MainModel
{
    use SoftDeletes;
    
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function reciver()
    {
        return $this->hasOne('App\User', 'id', 'reciver_id');
    }

    public function file_url()
    {
        if (!!$this->file_name) {
            return Storage::url($this->file_name);
        } else {
            return false;
        }
    }
}
