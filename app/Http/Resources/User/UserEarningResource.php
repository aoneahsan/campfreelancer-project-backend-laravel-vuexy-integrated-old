<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class UserEarningResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'user_id' => $this->id,
            // 'transactional_logs' => UserTransactionalResource::collection($this->transactionalLog),
            'netIncome' => !!$this->net_income_count ? $this->net_income_count : 0,
            'withdrawn' => !!$this->withdrawn_count ? $this->withdrawn_count : 0,
            'usedForPurchases' => !!$this->used_for_purchases_count ? $this->used_for_purchases_count : 0,
            'pendingClearance' => !!$this->pending_clearance_count ? $this->pending_clearance_count : 0,
            'availableForWithdrawal' => !!$this->account ? (!!$this->account->balance ? $this->account->balance : 0): 0,
            'totalCancelled' => !!$this->cancelled_orders_as_seller_count ? $this->cancelled_orders_as_seller_count : 0
        ];
    }
}
