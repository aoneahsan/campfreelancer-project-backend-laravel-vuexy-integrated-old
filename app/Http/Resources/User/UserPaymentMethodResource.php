<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class UserPaymentMethodResource extends JsonResource
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
            'payment_method_company' => $this->payment_method_company,
            'payment_method_name' => $this->payment_method_name,
            'payment_method_emailID' => $this->payment_method_emailID,
            'payment_method_username' => $this->payment_method_username,
            'payment_method_accountNumber' => $this->payment_method_accountNumber,
            'is_active' => !!$this->is_active,
            'payment_method_added_at' => $this->payment_method_added_at
        ];
    }
}
