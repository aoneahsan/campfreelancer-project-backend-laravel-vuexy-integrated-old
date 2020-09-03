<?php

namespace App\Model\Shared;

use App\Model\MainModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class MembershipPlan extends MainModel
{
    use SoftDeletes;

    // protected $fillable = [
    //     'title', 'description', 'price', 'plan_type', 'can_offer_requests', 'bids_allowed', 'commission_per_order', 'can_post_request', 'post_premium_requests', 'show_primium_request', 'can_add_gigs'
    // ];
    protected $guarded = [];
}