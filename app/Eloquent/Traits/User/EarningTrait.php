<?php

namespace App\Eloquent\Traits\User;

trait EarningTrait
{

    public function transactionalLog()
    {
        return $this->hasMany('App\Model\User\UserTransactionLog');
    }

    public function netIncome() // total earned
    {
        return $this->transactionalLog()->where('transaction_log_type', 'order_revenue');
    }

    public function withdrawn() // total withdrawn
    {
        return $this->transactionalLog()->where('transaction_log_type', 'withdrawal_completed');
    }

    public function usedForPurchases() // total withdrawn
    {
        return $this->transactionalLog()->where('transaction_log_type', 'order_placed');
    }

    public function pendingClearance() // total withdrawn
    {
        return $this->completedOrdersAsSeller()->where('is_cleared', false)->where('amount_added_in_seller_account_at', null);
    }
}
