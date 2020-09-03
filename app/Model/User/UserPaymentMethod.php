<?php

namespace App\Model\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserPaymentMethod extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    // protected $fillable = [
    //      'user_id' , 'payment_method_name', 'payment_method_emailID', 
    //      'payment_method_username' , 'payment_method_accountNumber', 'is_active', 
    //      'payment_method_added_at'
    // ];
}
