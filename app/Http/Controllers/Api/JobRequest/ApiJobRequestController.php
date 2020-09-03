<?php

namespace App\Http\Controllers\Api\JobRequest;

use App\Http\Controllers\Controller;
use App\Http\Resources\JobRequest\JobRequestCategoryFilterResource;
use App\Http\Resources\JobRequest\JobRequestResource;
use App\Http\Resources\JobRequest\PreviewJobRequestResource;
use App\Http\Resources\JobRequest\SaveJobRequestsResource;
use App\Model\App\AppSetting;
use App\Model\Gig\UserGig;
use App\Model\JobRequest\JobRequest;
use App\Model\JobRequest\SaveJobRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ApiJobRequestController extends Controller
{
    public function index(Request $request)
    {
        $allrequests = JobRequest::where('user_id', $request->user()->id)->with('category', 'subcategory', 'service_type', 'offers')->get();
        return response()->json(['data' => JobRequestResource::collection($allrequests)], 200);
    }

    public function getAvailableJobRequests(Request $request)
    {
        $allrequests = JobRequest::where('user_id', '!=', $request->user()->id)->with('buyer', 'category', 'subcategory', 'service_type', 'offers')->where('status', 'publish')->get();
        return response()->json(['data' => JobRequestResource::collection($allrequests)], 200);
    }

    public function store(Request $request)
    {
        // return response()->json(['data' => !!$request->has('file')], 500);

        $auto_review_job_requests = AppSetting::first()->auto_review_requests;

        $status = 'pending_approval';
        if (!!$auto_review_job_requests) {
            if ($request->status == 'pending_approval') {
                $status = 'publish';
            } else {
                $status = $request->status;
            }
        } else {
            if ($request->status == 'publish') {
                $status = 'pending_approval';
            } else {
                $status = $request->status;
            }
        }

        $fileName = null;
        if (!!$request->has('file')) {
            if ($request->hasFile('file')) {
                $fileName = $request->file('file')->store('jobrequests');
            }
        }

        $isHourly = false;
        if (!!$request->has('is_hourly')) {
            if ($request->is_hourly == 'true') {
                $isHourly = true;
            } else if ($request->is_hourly == 'false') {
                $isHourly = false;
            }
        } else {
            $isHourly = false;
        }

        $result = JobRequest::create([
            'user_id' => $request->user()->id,
            'category_id' => !!$request->has('category_id') ? $request->category_id : null,
            'subcategory_id' => !!$request->has('subcategory_id') ? $request->subcategory_id : null,
            'service_type_id' => !!$request->has('service_type_id') ? $request->service_type_id : null,
            'description' => !!$request->has('description') ? $request->description : null,
            'time' => !!$request->has('time') ? $request->time : null,
            'price' => !!$request->has('price') ? $request->price : null,
            'is_hourly' => !!$request->has('is_hourly') ? $isHourly : false,
            'buyer_location' => !!$request->has('location') ? $request->location : null,
            'request_type' => !!$request->has('request_type') ? $request->request_type : null,
            'file_name' => $fileName,
            'status' => $status
        ]);

        if ($result) {
            return response()->json(['data' => 'created!'], 200);
        } else {
            return response()->json(['data' => 'Error Occured!'], 500);
        }
    }

    public function getJobRequestDataToEdit($id)
    {
        $request_data = JobRequest::where('id', $id)->with('buyer', 'category', 'subcategory', 'service_type', 'offers')->first();
        if ($request_data) {
            return response()->json(['data' => new JobRequestResource($request_data)], 200);
        } else {
            return response()->json(['data' => "No Job Request Found!"], 404);
        }
    }

    public function show($id)
    {
        $request_data = JobRequest::where('id', $id)->with('buyer', 'category', 'subcategory', 'service_type', 'offers')->first();
        if ($request_data) {
            return response()->json(['data' => new PreviewJobRequestResource($request_data)], 200);
        } else {
            return response()->json(['data' => "No Job Request Found!"], 404);
        }
    }

    public function update(Request $request, $id)
    {
        // return response()->json(['data' => $request->jobRequestData], 500);

        $job_request = JobRequest::where('id', $id)->first();

        $auto_review_job_requests = AppSetting::first()->auto_review_requests;
        $status = 'pending_approval';
        if (!!$auto_review_job_requests) {
            if ($request->status == 'pending_approval') {
                $status = 'publish';
            } else {
                $status = $request->status;
            }
        } else {
            if ($request->status == 'publish') {
                $status = 'pending_approval';
            } else {
                $status = $request->status;
            }
        }

        $fileName = null;
        if (!!$request->has('file')) {
            if ($request->hasFile('file')) {
                $fileName = $request->file('file')->store('jobrequests');
                Storage::delete($job_request->file_name);
            }
        }

        $isHourly = false;
        if (!!$request->has('is_hourly')) {
            if ($request->is_hourly == 'true') {
                $isHourly = true;
            } else if ($request->is_hourly == 'false') {
                $isHourly = false;
            }
        } else {
            $isHourly = false;
        }

        $result = $job_request->update([
            'category_id' => !!$request->has('category_id') ? $request->category_id : $job_request->category_id,
            'subcategory_id' => !!$request->has('subcategory_id') ? $request->subcategory_id : $job_request->subcategory_id,
            'service_type_id' => !!$request->has('service_type_id') ? $request->service_type_id : $job_request->service_type_id,
            'description' => !!$request->has('description') ? $request->description : $job_request->description,
            'time' => !!$request->has('time') ? $request->time : $job_request->time,
            'price' => !!$request->has('price') ? $request->price : $job_request->price,
            'is_hourly' => !!$request->has('is_hourly') ? $isHourly : $job_request->is_hourly,
            'buyer_location' => !!$request->has('location') ? $request->location : $job_request->location,
            'request_type' => !!$request->has('request_type') ? $request->request_type : $job_request->request_type,
            'file_name' => $fileName ? $fileName : $job_request->file_name,
            'status' => $status
        ]);

        if ($result) {
            return response()->json(['data' => 'Updated!'], 200);
        } else {
            return response()->json(['data' => 'Error Occured!'], 500);
        }
    }

    public function destroy($id)
    {
        $request_data = JobRequest::where('id', $id)->first();
        if ($request_data) {
            $result = $request_data->delete();
            if ($result) {
                return response()->json(['data' => 'Deleted!'], 200);
            } else {
                return response()->json(['data' => "Error Occured!"], 500);
            }
        } else {
            return response()->json(['data' => "No Job Request Found!"], 500);
        }
    }

    // *************************************************************************************************

    // ************************************************************************
    // Save Jobs Requests Code Start
    // ************************************************************************
    public function getAllSaveJobRequests(Request $request)
    {
        // DB::enableQueryLog();
        $saverequests = SaveJobRequest::where('user_id', $request->user()->id)->with('requestdetails', 'buyerdetails')->get();
        return response()->json(['data' => SaveJobRequestsResource::collection($saverequests)], 200);
        // return DB::getQueryLog();
    }

    public function saveJobRequest(Request $request)
    {
        $request->validate([
            'buyer_id' => 'required',
            'job_request_id' => 'required'
        ]);

        $result = SaveJobRequest::create([
            'user_id' => $request->user()->id,
            'job_request_id' => $request->job_request_id,
            'buyer_id' => $request->buyer_id
        ]);

        if ($result) {
            return response()->json(['data' => 'Created!'], 200);
        } else {
            return response()->json(['data' => 'Error Occured!'], 500);
        }
    }

    public function deleteJobRequest($id)
    {

        $result = SaveJobRequest::where('id', $id)->delete();
        if ($result) {
            return response()->json(['data' => 'Deleted!'], 200);
        } else {
            return response()->json(['data' => 'Error Occured!'], 500);
        }
    }
    // ************************************************************************
    // Save Jobs Requests Code Ends
    // ************************************************************************

    // *************************************************************************************************

    // ************************************************************************
    // Jobs Requests For Seller Code Starts
    // ************************************************************************
    public function getJobRequests(Request $request)
    {
        $items = JobRequest::join('user_gigs', 'user_gigs.subcategory_id', '=', 'job_requests.subcategory_id')->where('user_gigs.user_id', $request->user()->id)->with('category', 'subcategory', 'service_type', 'offers')->select('job_requests.*')->get();
        return response()->json(['data' => JobRequestResource::collection($items)], 200);
        // DB::enableQueryLog();
        // $items = JobRequest::whereIn('subcategory_id', UserGig::where('user_id', $request->user()->id)->pluck('subcategory_id'))->with('category', 'subcategory', 'service_type', 'offers')->get();
        // $items = JobRequest::join('user_gigs', 'user_gigs.subcategory_id', '=', 'job_requests.subcategory_id')->where('user_gigs.user_id', $request->user()->id)->select('job_requests.*')->get();
        // $items = JobRequest::with([
        //     'userGigs' => function($userGigs) use($request) {
        //         $userGigs->where('user_id', $request->user()->id);
        //     }
        // ])->get();
        // return $items;
        // return DB::getQueryLog();
    }

    public function userGigCategories(Request $request)
    {
        // $items = UserGig::where('user_id', $request->user()->id)->with('subcategory')->get();
        $items = UserGig::join('gig_categories', 'gig_categories.id', '=', 'user_gigs.subcategory_id')->select('gig_categories.*')->get();
        // return $items;
        return response()->json(['data' => JobRequestCategoryFilterResource::collection($items)], 200);
    }

    // ************************************************************************
    // Jobs Requests For Seller Code Ends
    // ************************************************************************

    public function changeJobRequestStatus(Request $request, $id)
    {

        $jobRequest = JobRequest::where('id', $id)->first();
        if (!$jobRequest) {
            return response()->json(['message' => "No Gig Found!"], 500);
        }

        $auto_review_job_requests = AppSetting::first()->auto_review_requests;
        $status = 'pending_approval';
        if (!!$auto_review_job_requests) {
            $status = $request->status;
        } else {
            if ($request->status == 'publish') {
                $status = 'pending_approval';
            }
        }

        $jobRequest->update([
            'status' => $status
        ]);

        if ($jobRequest) {
            return response()->json(['data' => 'Updated!'], 200);
        } else {
            return response()->json(['message' => 'Error Occured!'], 500);
        }
    }

    public function deleteJobRequestsWithStatus(Request $request, $status)
    {
        $gig = JobRequest::where('user_id', $request->user()->id)->where('status', $status)->delete();
        if ($gig) {
            return response()->json(['data' => 'Deleted!'], 200);
        } else {
            return response()->json(['message' => 'Error Occured!'], 500);
        }
    }
}
