<?php

namespace App\Model\User;

use App\Model\MainModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserAccount extends MainModel
{

    use SoftDeletes;
    protected $guarded = [];
    
    // protected $fillable = [
    //     'user_id', 'balance'
    // ];
}
