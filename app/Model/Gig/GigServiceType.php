<?php

namespace App\Model\Gig;

use App\Model\MainModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class GigServiceType extends MainModel
{

    use SoftDeletes;
    
    // protected $fillable = [
    //     'category_id', 'title', 'description', 'sort_order', 'is_visible'
    // ];
    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo('App\Model\Gig\GigCategory', 'category_id', 'id');
    }
}
