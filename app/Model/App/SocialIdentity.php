<?php

namespace App\Model\App;

use App\Model\MainModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class SocialIdentity extends MainModel
{
    use SoftDeletes;
    
    // protected $fillable = [
    //     'user_id', 'provider_name', 'provider_id'
    // ];
    protected $guarded = [];

    public function user() {
        return $this->belongsTo('App\User');
    }
}
