<?php

namespace App\Http\Controllers\Api\JobOffer;

use App\Http\Controllers\Controller;
use App\Http\Resources\JobOffer\JobOfferResource;
use App\Model\Offer\Offer;
use Illuminate\Http\Request;

class ApiJobOfferController extends Controller
{
    public function index(Request $request)
    {
        $items = Offer::where('user_id', $request->user()->id)->with('gig', 'buyer', 'jobrequest')->get();
        return response()->json(['data' => JobOfferResource::collection($items)], 200);
    }

    public function store(Request $request)
    {
        // $request->validate([
        //     'job_request_id' => 'required',
        //     'buyer_id' => 'required',
        //     'gig_id' => 'required',
        //     'description' => 'required',
        //     'price' => 'required',
        //     'time' => 'required',
        //     'no_of_revisions' => 'required',
        //     'ask_for_gig_requirements' => 'required'
        // ]);

        $offerExists = Offer::where('user_id', $request->user()->id)->where('job_request_id', $request->job_request_id)->first();
        if ($offerExists) {
            return response()->json(['message' => 'Offer Placed Already!'], 409);
        }

        $result = Offer::create([
            'user_id' => $request->user()->id,
            'job_request_id' => $request->has('job_request_id') ? $request->job_request_id : null,
            'buyer_id' => $request->has('buyer_id') ? $request->buyer_id : null,
            'gig_id' => $request->has('gig_id') ? $request->gig_id : null,
            'description' => $request->has('description') ? $request->description : null,
            'price' => $request->has('price') ? $request->price : null,
            'time' => $request->has('time') ? $request->time : null,
            'no_of_revisions' => $request->has('no_of_revisions') ? $request->no_of_revisions : null,
            'ask_for_gig_requirements' => $request->ask_for_gig_requirements,
            'status' => $request->has('status') ? $request->status : 'publish'
        ]);
        if ($result) {
            return response()->json(['data' => 'Created!'], 200);
        } else {
            return response()->json(['data' => 'Error Occured!'], 500);
        }
    }

    public function show($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
