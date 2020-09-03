<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

use Illuminate\Support\Facades\Storage;

use App\Eloquent\Traits\User\UserRelationsTrait;
use App\Eloquent\Traits\User\OrdersTrait;
use App\Eloquent\Traits\User\EarningTrait;
use App\Eloquent\Traits\User\AnalyticsTrait;
use App\Eloquent\Traits\User\OrderRatingTrait;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens, HasRoles, SoftDeletes, UserRelationsTrait, OrdersTrait, OrderRatingTrait, EarningTrait, AnalyticsTrait;

    // protected $fillable = [
    //     'username', 'name', 'email', 'password', 'phone_number', 'country_code', 'profile_img', 'role', 'is_buyer', 'authy_id', 'is_2fa_verified', 'is_2fa_enabled', 'seller_plan_id', 'buyer_plan_id'
    // ];

    protected $guarded = [];

    public static $new = null;

    protected $hidden = [
        'password', 'remember_token'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime'
    ];

    // Get login package tokken function
    public function getTokken()
    {
        // return $this->createToken($request->device_name)->plainTextToken;
        return $this->createToken('mobile')->plainTextToken;
    }

    // User Profile Image function
    public function getProfileImg()
    {
        if (!!$this->profile_img) {
            return Storage::url($this->profile_img);
        } else {
            return 'assets/img/placeholder.jpg';
        }
    }
}
