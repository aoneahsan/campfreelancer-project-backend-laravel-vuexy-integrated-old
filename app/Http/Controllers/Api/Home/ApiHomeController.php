<?php

namespace App\Http\Controllers\Api\Home;

use App\Http\Controllers\Controller;
use App\Http\Resources\Gig\GigParentCategoryResource;
use App\Http\Resources\Home\HomeGigsResource;
use App\Http\Resources\Order\OrderFeedbackResource;
use App\Model\App\AppSetting;
use App\Model\Gig\GigCategory;
use App\Model\Gig\UserGig;
use App\Model\Order\OrderFeedback;
use Illuminate\Http\Request;

class ApiHomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $popularCategories = GigCategory::where('is_popular', true)->limit(10)->get();
        $homeMapFreelancers = UserGig::where('is_home_map_feature_item', true)->with('category', 'user')->get();
        $homeFindExpertsFreelancers = UserGig::where('is_home_expert_section_item', true)->with('category', 'user')->get();
        $reviews = OrderFeedback::where('buyer_rating', '>=', 4.5)->with('buyer', 'seller')->get();

        $appSettings = AppSetting::first();
        $homeMapSectionIncrements = $appSettings->home_map_numbers_increment;
        $homeReviewSectionVideo = $appSettings->home_review_section_video;
        $homeData = [
            'popularCategories' => GigParentCategoryResource::collection($popularCategories),
            'homeMapFreelancers' => HomeGigsResource::collection($homeMapFreelancers),
            'homeFindExpertsFreelancers' => HomeGigsResource::collection($homeFindExpertsFreelancers),
            'homeMapSectionIncrements' => json_decode($homeMapSectionIncrements),
            'homeReviewSectionVideo' => json_decode($homeReviewSectionVideo),
            'reviews' => OrderFeedbackResource::collection($reviews)
        ];
        return response()->json(['data' => $homeData], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
