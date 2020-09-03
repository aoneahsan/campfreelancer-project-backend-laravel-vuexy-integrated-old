<?php

namespace App\Model\Offer;

use App\Model\MainModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Offer extends MainModel
{
    use SoftDeletes;

    // protected $fillable = [
    //     'user_id', 'job_request_id', 'buyer_id', 'gig_id', 'description', 'price', 'time', 'status', 'no_of_revisions', 'ask_for_gig_requirements'
    // ];
    protected $guarded = [];

    public function gig()
    {
        return $this->hasOne('App\Model\Gig\UserGig', 'id', 'gig_id');
    }

    public function seller()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function buyer()
    {
        return $this->hasOne('App\User', 'id', 'buyer_id');
    }

    public function jobrequest()
    {
        return $this->hasOne('App\Model\JobRequest\JobRequest', 'id', 'job_request_id');
    }
}
