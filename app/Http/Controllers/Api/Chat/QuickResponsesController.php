<?php

namespace App\Http\Controllers\Api\Chat;

use App\Http\Controllers\Controller;
use App\Http\Resources\Chat\QuickResponsesResource;
use App\Model\Chat\QuickResponse;
use Illuminate\Http\Request;

class QuickResponsesController extends Controller
{
    public function index(Request $request)
    {
        $user_id = $request->user()->id;
        $quick_responses = QuickResponse::where('user_id', $user_id)->get();
        return response()->json(['data' => QuickResponsesResource::collection($quick_responses)], 200);
    }

    public function store(Request $request)
    {
        $user_id = $request->user()->id;
        $quick_response = QuickResponse::create([
            'user_id' => $user_id,
            'title' => !!$request->has('title') ? $request->title : null,
            'message' => !!$request->has('message') ? $request->message : null,
            'is_favorite' => !!$request->has('is_favorite') ? $request->is_favorite : null,
            'type' => !!$request->has('type') ? $request->type : null
        ]);
        
        if ($quick_response) {
            return response()->json(['data' => 'Added!'], 200);
        } else {
            return response()->json(['message' => 'Error Occured!'], 500);
        }
    }

    public function show($quickResponse)
    {
        //
    }

    public function update(Request $request, $quickResponse)
    {
        $quick_response = QuickResponse::where('id', $quickResponse)->update([
            'title' => !!$request->has('title') ? $request->title : null,
            'message' => !!$request->has('message') ? $request->message : null,
            'is_favorite' => !!$request->has('is_favorite') ? $request->is_favorite : null,
            'type' => !!$request->has('type') ? $request->type : null
        ]);
        
        if ($quick_response) {
            return response()->json(['data' => 'Updated!'], 200);
        } else {
            return response()->json(['message' => 'Error Occured!'], 500);
        }
    }

    public function destroyAllQuickResponses(Request $request)
    {
        $user_id = $request->user()->id;
        $quick_response = QuickResponse::where('user_id', $user_id)->delete();
        if ($quick_response) {
            return response()->json(['data' => 'All Deleted!'], 200);
        } else {
            return response()->json(['message' => 'Error Occured!'], 500);
        }
    }

    public function destroy($quickResponse)
    {
        $quick_response = QuickResponse::where('id', $quickResponse)->delete();
        if ($quick_response) {
            return response()->json(['data' => 'Deleted!'], 200);
        } else {
            return response()->json(['message' => 'Error Occured!'], 500);
        }
    }
}
