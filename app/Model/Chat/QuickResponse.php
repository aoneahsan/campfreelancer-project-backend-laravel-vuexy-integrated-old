<?php

namespace App\Model\Chat;

use App\Model\MainModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuickResponse extends MainModel
{
    use SoftDeletes;
    
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
