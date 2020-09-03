<?php

namespace App\Http\Controllers\Api\Gig;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Resources\Gig\GigParentCategoryResource;
use App\Http\Resources\Gig\GigCategoryServiceTypeResource;
use App\Http\Resources\Gig\GigListingResource;
use App\Model\App\AppSetting;
use App\Model\Gig\UserGig;
use App\Model\Gig\GigCategory;
use App\Model\Gig\GigPackage;
use App\Model\Gig\GigServiceType;
use App\Model\Order\OrderFeedback;
use App\Model\User\UserDetails;
use App\User;
use Illuminate\Support\Facades\DB;

class ApiGigListingController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'applyFilters' => 'required|boolean'
        ]);

        $gigs = UserGig::where('status', 'publish')
            ->with('category', 'subcategory', 'servicetype', 'gallery', 'packages', 'requirements', 'user')
            ->withCount(
                [
                    'gigRatings' => function ($query) {
                        $query->select(DB::raw('avg(buyer_rating)'));
                    },
                    'gigSoldOrders'
                ]
            );
        if ($request->applyFilters == false) {
            $finalData = $gigs->get();
            return response()->json(['data' => GigListingResource::collection($finalData)], 200);
        } else if ($request->applyFilters == true) {
            if (isset($request->filters['query'])) {
                $gigs->where('title', 'LIKE', "%{$request->filters['query']}%");
            }
            if (isset($request->filters['category'])) {
                $category = GigCategory::where('slug', $request->filters['category'])->first();
                $gigs->where('category_id', $category->id);
            }
            if (isset($request->filters['location'])) {
                $userIDs = UserDetails::where('location', $request->filters['location'])->groupBy('user_id')->pluck('user_id');
                $gigs->whereIn('user_id', $userIDs);
            }
            if (isset($request->filters['min_price'])) {
                $gigsCpoy = $gigs;
                $gigIDs = $gigsCpoy->pluck('id');
                $newGigIDs = GigPackage::whereIn('gig_id', $gigIDs)->where('price', '>=', $request->filters['min_price'])->groupBy('gig_id')->pluck('gig_id');
                $gigs->whereIn('id', $newGigIDs);
            }
            if (isset($request->filters['max_price'])) {
                $gigsCpoy = $gigs;
                $gigIDs = $gigsCpoy->pluck('id');
                $newGigIDs = GigPackage::whereIn('gig_id', $gigIDs)->where('price', '<=', $request->filters['max_price'])->groupBy('gig_id')->pluck('gig_id');
                $gigs->whereIn('id', $newGigIDs);
            }
            if (isset($request->filters['delivery_time'])) {
                $gigsCpoy = $gigs;
                $gigIDs = $gigsCpoy->pluck('id');
                $newGigIDs = GigPackage::whereIn('gig_id', $gigIDs)->where('time', '<=', $request->filters['delivery_time'])->groupBy('gig_id')->pluck('gig_id');
                $gigs->whereIn('id', $newGigIDs);
            }
            if (isset($request->filters['rating'])) {
                $gigsCpoy = $gigs;
                $gigIDs = $gigsCpoy->pluck('id');
                $newGigIDs = OrderFeedback::whereIn('gig_id', $gigIDs)->where('buyer_rating', '>=', $request->filters['rating'])->groupBy('gig_id')->pluck('gig_id');
                $gigs->whereIn('id', $newGigIDs);
            }
            $finalData = $gigs->get();
            return response()->json(['data' => GigListingResource::collection($finalData)], 200);
        } else {
            return response()->json(['message' => "Invalid filters, refresh page"], 400);
        }
    }

    public function getListingFilters()
    {
        $items = AppSetting::first()->gig_category_listing_page_filters;
        if (!!$items) {
            return response()->json(['data' => json_decode($items)], 200);
        } else {
            return response()->json(['data' => null], 200);
        }
    }

    public function getParentCategories(Request $request)
    {
        $categories = GigCategory::where('is_parent', true)->get();
        return response()->json(['data' => GigParentCategoryResource::collection($categories)], 200);
    }

    public function getChildCategories(Request $request, $parentId)
    {
        $categories = GigCategory::where('parent_id', $parentId)->get();
        return response()->json(['data' => GigParentCategoryResource::collection($categories)], 200);
    }

    public function getCategoryServiceTypes(Request $request, $childID)
    {
        $servicetypes = GigServiceType::where('category_id', $childID)->get();
        return response()->json(['data' => GigCategoryServiceTypeResource::collection($servicetypes)], 200);
    }

    public function getGigCategoryDetail(Request $request, $slug)
    {
        $item = GigCategory::where('slug', $slug)->first();
        if ($item) {
            return response()->json(['data' => new GigCategoryServiceTypeResource($item)], 200);
        }
        else {
            return response()->json(['message' => 'No Gig Category Found!'], 404);
        }
    }
}
