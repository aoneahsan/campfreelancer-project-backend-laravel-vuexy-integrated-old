<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class UserTransactionalResource extends JsonResource
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
            'id' => $this->id,
            'user_id' => $this->user_id,
            'order_id' => $this->order_id,
            'order_number' => $this->order_number,
            'transaction_log_type' => $this->transaction_log_type,// order_revenue | funds_cleared | withdrawal_initiated | withdrawal_completed | withdrawal_cancelled | order_placed(this is to get used_for_purchase)
            'amount' => $this->amount,
            'log_created_at' => $this->log_created_at,
            'order_earning_clearnace_date' => $this->order_earning_clearnace_date, // just used in one case if this log is created on 'order_revenue' (menu on order completetion) so i simply saved the date here to get the clearance date :)
            'created_at' => $this->created_at
        ];
    }
}
