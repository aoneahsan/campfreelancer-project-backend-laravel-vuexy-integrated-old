<?php

namespace App\Model\Gig;

use App\Model\MainModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class GigAnalytics extends MainModel
{
    use SoftDeletes;
    
    // protected $fillable = [
    //     'gig_id', 'type'
    // ];
    protected $guarded = [];
}
