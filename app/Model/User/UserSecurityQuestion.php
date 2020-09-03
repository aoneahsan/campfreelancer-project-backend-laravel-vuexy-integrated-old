<?php

namespace App\Model\User;

use App\Model\MainModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserSecurityQuestion extends MainModel
{

    use SoftDeletes;
    
    // protected $fillable = [
    //     'user_id', 'question', 'answer', 'extra_field'
    // ];
    protected $guarded = [];
}
