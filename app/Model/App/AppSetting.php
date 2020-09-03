<?php

namespace App\Model\App;

use App\Model\MainModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppSetting extends MainModel
{
    use SoftDeletes;

    // protected $fillable = [
    //     'auto_review_requests', 'auto_review_gigs'
    // ];

    protected $guarded = [];
}
