<?php

namespace App\Model\JobRequest;

use App\Model\MainModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class JobRequest extends MainModel
{
    use SoftDeletes;

    // protected $fillable = [
    //     'user_id', 'category_id', 'subcategory_id', 'service_type_id', 'description', 'file_name', 'time', 'status', 'price', 'price_type', 'buyer_location', 'request_type'
    // ];
    protected $guarded = [];

    public function file_url()
    {
        if (!!$this->file_name) {
            return Storage::url($this->file_name);
        } else {
            return false;
        }
    }

    public function buyer()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function category()
    {
        return $this->hasOne('App\Model\Gig\GigCategory', 'id', 'category_id');
    }

    public function subcategory()
    {
        return $this->hasOne('App\Model\Gig\GigCategory', 'id', 'subcategory_id');
    }

    public function service_type()
    {
        return $this->hasOne('App\Model\Gig\GigServiceType', 'id', 'service_type_id');
    }

    public function offers()
    {
        return $this->hasMany('App\Model\Offer\Offer');
    }

    public function userGigs()
    {
        return $this->hasMany('App\Model\Gig\UserGig', 'subcategory_id', 'subcategory_id');
    }
}
