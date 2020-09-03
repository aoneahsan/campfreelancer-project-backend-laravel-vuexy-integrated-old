<?php

namespace App\Model\Gig;

use App\Model\MainModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class GigRequirement extends MainModel
{

    use SoftDeletes;
    
    // protected $fillable = [
    //     'gig_id', 'title', 'description', 'file_name', 'is_required'
    // ];
    protected $guarded = [];
}
