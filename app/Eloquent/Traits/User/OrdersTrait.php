<?php

namespace App\Eloquent\Traits\User;

use Carbon\Carbon;
// use Illuminate\Database\Eloquent\Builder;

trait OrdersTrait
{
    public function ordersAsBuyer()
    {
        return $this->hasMany('App\Model\Order\Order', 'buyer_id', 'id');
    }

    public function ordersAsSeller()
    {
        return $this->hasMany('App\Model\Order\Order', 'seller_id', 'id');
    }

    public function completedOrdersAsBuyer()
    {
        return $this->hasMany('App\Model\Order\Order', 'buyer_id', 'id')->where('status', 'completed');
    }

    public function activeOrdersAsSeller()
    {
        return $this->hasMany('App\Model\Order\Order', 'seller_id', 'id')->whereNotIn('status', ['completed', 'cancelled']);
    }

    public function completedOrdersAsSeller()
    {
        return $this->hasMany('App\Model\Order\Order', 'seller_id', 'id')->where('status', 'completed');
    }

    public function avarageSellingPrice()
    {
        return $this->completedOrdersAsSeller();
    }

    public function completedOrdersAsSellerSpecificRange($specificDateRange = null)
    {
        if ($specificDateRange != null) {
            return $this->hasMany('App\Model\Order\Order', 'seller_id', 'id')->where('status', 'completed')->where('created_at', '<=', $specificDateRange);
        } else {
            return $this->hasMany('App\Model\Order\Order', 'seller_id', 'id')->where('status', 'completed');
        }
    }

    public function cancelledOrdersAsSeller()
    {
        return $this->hasMany('App\Model\Order\Order', 'seller_id', 'id')->where('status', 'cancelled');
    }

    public function ordersEarnings($days)
    {
        $date = Carbon::today()->subDays($days);
        return $this->completedOrdersAsSeller()->where('created_at', '>=', $date)->sum('seller_earning');
    }

    public function ordersPendingClearance($days)
    {
        $date = Carbon::today()->subDays($days);
        return $this->completedOrdersAsSeller()->where('is_cleared', 0)->where('created_at', '>=', $date)->sum('seller_earning');
    }

    public function ordersWithdrawable($days)
    {
        $date = Carbon::today()->subDays($days);
        return $this->completedOrdersAsSeller()->where('withdrawn_at', null)->where('is_cleared', 0)->where('created_at', '>=', $date)->sum('seller_earning');
    }

    // public function scopeCompletedOrdersTrait(Builder $builder)
    // {
    //     // dd($builder);
    //     // return $builder;
    // }
}
