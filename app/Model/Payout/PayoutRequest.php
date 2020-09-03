<?php

namespace App\Model\Payout;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PayoutRequest extends Model
{
    use SoftDeletes;
    protected $guarded = [];
}
