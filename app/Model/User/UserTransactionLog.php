<?php

namespace App\Model\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserTransactionLog extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    // order_revenue | 
    // funds_pending_clearance | 
    // funds_cleared | 
    // withdrawal_initiated | 
    // withdrawal_completed | 
    // withdrawal_cancelled | 
    // order_placed(this is to get used_for_purchase)

    public function scopeTransanctionalType($query, $transactionalLogType) // total earned
    {
        return $query->where('transaction_log_type', $transactionalLogType);
    }
}
