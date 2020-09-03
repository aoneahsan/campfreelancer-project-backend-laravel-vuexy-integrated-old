<?php

namespace App\Model\User;

use App\Model\MainModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserDetails extends MainModel
{

    use SoftDeletes;
    
    // protected $fillable = [
    //     'user_id', 'cnic', 'location', 'city', 'country', 'cnic_front', 'cnic_back', 'facebook_link', 'linkedin_link', 'twitter_link', 'github_link'
    // ];
    protected $guarded = [];
}
