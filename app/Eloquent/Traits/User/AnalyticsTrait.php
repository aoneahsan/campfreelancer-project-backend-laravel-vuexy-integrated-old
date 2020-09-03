<?php

namespace App\Eloquent\Traits\User;

use App\User;
use Illuminate\Database\Eloquent\Builder;

trait AnalyticsTrait
{

    // User Analytics
    public function scopeWithAnalytics(Builder $builder)
    {
        // dd($builder);
        // return $query->where(function ($query) {
            // $query->where();
        // });
    }
}
