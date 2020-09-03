<?php

namespace App\Eloquent\Traits\User;

trait UserRelationsTrait
{
    // SocialIdentity Plugin Function
    public function identities()
    {
        return $this->hasMany('App\Model\App\SocialIdentity');
    }

    // UserAccount Relation
    public function account()
    {
        return $this->hasOne('App\Model\User\UserAccount');
    }

    // UserDetails Relation
    public function details()
    {
        return $this->hasOne('App\Model\User\UserDetails');
    }

    // User Security Questions function
    public function securityQuestions()
    {
        return $this->hasMany('App\Model\User\UserSecurityQuestion');
    }

    // User Payment Methods
    public function paymentMethods()
    {
        return $this->hasMany('App\Model\User\UserPaymentMethod');
    }

    // User Gigs
    public function gigs()
    {
        return $this->hasMany('App\Model\Gig\UserGig', 'user_id', 'id')->where('status', self::$new);
    }

    // User Membership Plan Details relation function
    public function membershipplandetails()
    {
        return $this->hasOne('App\Model\Shared\MembershipPlan', 'id', 'membership_plan_id');
    }
}