<?php

namespace App\Http\Controllers\Api\Gig;

use App\Http\Controllers\MainController;
use Illuminate\Http\Request;

use App\Model\Gig\GigServiceType;

use App\Http\Resources\Gig\GigServiceTypeResource;

use Illuminate\Support\Str;

class ApiGigServiceTypeController extends MainController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = GigServiceType::with('category')->get();
        return response()->json(['data' => GigServiceTypeResource::collection($items)], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'category_id' => 'required',
            'sort_order' => 'numeric',
            'is_visible' => 'boolean'
        ]);
        
        $result = GigServiceType::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'description' => $request->description,
            'category_id' => $request->category_id,
            'sort_order' => $request->sort_order ? $request->sort_order : 0,
            'is_visible' => $request->is_visible ? $request->is_visible : true
        ]);

        if ($result) {
            return response()->json(['data' => 'Created'], 200);
        } else {
            return response()->json(['message' => 'Error Occured!'], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = GigServiceType::where('id', $id)->orWhere('slug', $id)->with('category')->first();
        if ($item) {
            return response()->json(['data' => new GigServiceTypeResource($item)], 200);
        } else {
            return response()->json(['message' => 'No Category Found!'], 500);
        }
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
        $item = GigServiceType::where('id', $id)->orWhere('slug', $id)->first();
        
        $result = GigServiceType::where('id', $id)->update([
            'title' => $request->has('title') ? $request->title : $item->title,
            'slug' => $request->has('title') ? Str::slug($request->title) : $item->slug,
            'description' => $request->has('description') ? $request->description : $item->description,
            'category_id' => $request->has('category_id') ? $request->category_id : $item->category_id,
            'sort_order' => $request->has('sort_order') ? $request->sort_order : $item->sort_order,
            'is_visible' => $request->has('is_visible') ? $request->is_visible : $item->is_visible
        ]);

        if ($result) {
            return response()->json(['data' => 'Updated!'], 200);
        } else {
            return response()->json(['message' => 'Error Occured!'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = GigServiceType::where('id', $id)->orWhere('slug', $id)->delete();
        if ($result) {
            return response()->json(['data' => 'Deleted!'], 200);
        } else {
            return response()->json(['message' => 'Error Occured!'], 500);
        }
    }
}
