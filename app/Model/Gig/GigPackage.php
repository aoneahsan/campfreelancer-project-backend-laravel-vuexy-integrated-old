<?php

namespace App\Model\Gig;

use App\Model\MainModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class GigPackage extends MainModel
{
    use SoftDeletes;
    
    // protected $fillable = [
    //     'gig_id', 'title', 'description', 'price', 'time', 'sort_order', 'is_visible'
    // ];
    protected $guarded = [];
}
