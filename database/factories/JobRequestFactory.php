<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\Gig\GigCategory;
use App\Model\Gig\GigServiceType;
use App\Model\JobRequest\JobRequest;
use App\User;
use Faker\Generator as Faker;

$factory->define(JobRequest::class, function (Faker $faker) {
    // $user = User::pluck('id')->ramdom();
    // $parentCat = GigCategory::where('is_parent', 1)->pluck('id')->id;
    // $childCat = GigCategory::where('parent_id', $parentCat)->pluck('id')->id;
    // $serviceType = GigServiceType::where('category_id', $childCat)->pluck('id')->id;
    $user = 4;
    $parentCat = 1;
    $childCat = 5;
    $serviceType = 1;
    return [
        'user_id' => $user,
        'category_id' => $parentCat,
        'subcategory_id' => $childCat,
        'service_type_id' => $serviceType,
        'description' => $faker->text(130),
        'time' => 5,
        'price' => $faker->numberBetween(1000, 10000),
        'price_type' => function() {
            $array = ['hourly', 'fixed'];
            return rand($array);
        },
        'buyer_location' => 'Pakistan',
        'request_type' => 'basic', // this is membership package part, basic request, featured request, etc
        'status' => 'publish'
    ];
});
