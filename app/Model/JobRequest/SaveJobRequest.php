<?php

namespace App\Model\JobRequest;

use App\Model\MainModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaveJobRequest extends MainModel
{
    use SoftDeletes;
    
    // protected $fillable = [
    //     'user_id', 'job_request_id', 'buyer_id'
    // ];
    protected $guarded = [];

    public function sellerdetails()
    {
        return $this->belongsTo('App\User', 'id', 'user_id');
    }

    public function requestdetails()
    {
        return $this->hasOne('App\Model\JobRequest\JobRequest', 'id', 'job_request_id');
    }

    public function buyerdetails()
    {
        return $this->hasOne('App\User', 'id', 'buyer_id');
    }
}
