<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\MainController;
use App\Http\Resources\Chat\OtherUserDataResource;
use App\Http\Resources\User\SearchUserResource;
// Models
use App\Http\Resources\User\UserProfileResource;
// use App\Model\User\UserAccount;
use App\Http\Resources\User\UserSecurityQuestionResource;
use App\Model\User\UserAccount;
use App\Model\User\UserDetails;

// Resources
use App\Model\User\UserSecurityQuestion;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ApiUserController extends MainController
{
    public function getUserPermissions(Request $request)
    {
        $permissions = $request->user()->getAllPermissions();
        return response()->json(['data' => $permissions], 404);
    }

    public function getSpecificUserProfileData(Request $request, $userID)
    {
        $user = User::where('id', $userID)
            ->orWhere('username', $userID)
            ->with('account', 'details', 'orderRatingAsSeller')
            ->withCount(
                [
                    'communicationRatingAsSeller' => function ($query) {
                        $query->select(DB::raw('avg(buyer_rating_sellerCommunication)'));
                    },
                    'serviceAsDescribedRatingAsSeller' => function ($query) {
                        $query->select(DB::raw('avg(buyer_rating_serviceAsDescribed)'));
                    },
                    'recommendRatingAsSeller' => function ($query) {
                        $query->select(DB::raw('avg(buyer_rating_sellerRecommended)'));
                    },
                    'orderRatingAsSeller' => function ($query) {
                        $query->select(DB::raw('avg(buyer_rating)'));
                    }
                ]
            )
            ->first();
        if ($user) {
            return response()->json(['data' => new OtherUserDataResource($user)], 200);
        } else {
            return response()->json(['message' => 'User Not Found!'], 500);
        }
    }

    public function getUserProfileData(Request $request)
    {
        $user = User::where('id', $request->user()->id)
            ->with('account', 'details', 'membershipplandetails', 'paymentMethods', 'orderRatingAsSeller')
            ->withCount(
                [
                    'communicationRatingAsSeller' => function ($query) {
                        $query->select(DB::raw('avg(buyer_rating_sellerCommunication)'));
                    },
                    'serviceAsDescribedRatingAsSeller' => function ($query) {
                        $query->select(DB::raw('avg(buyer_rating_serviceAsDescribed)'));
                    },
                    'recommendRatingAsSeller' => function ($query) {
                        $query->select(DB::raw('avg(buyer_rating_sellerRecommended)'));
                    },
                    'orderRatingAsSeller' => function ($query) {
                        $query->select(DB::raw('avg(buyer_rating)'));
                    }
                ]
            )
            ->first();

        // dd($user->toArray());
        if ($user) {
            return response()->json(['data' => new UserProfileResource($user)], 200);
        } else {
            return response()->json(['message' => 'User Not Found!'], 500);
        }
    }

    public function updateUserProfile(Request $request)
    {
        // return response()->json(['message' => $request->toArray()], 500);
        $user_data = User::where('id', $request->user()->id)->with('account', 'details')->first();

        $result = User::where('id', $request->user()->id)->update([
            'username' => $request->has('username') ? $request->username : $user_data->username,
            'name' => $request->has('name') ? $request->name : $user_data->name,
            'email' => $request->has('email') ? $request->email : $user_data->email,
            'phone_number' => $request->has('phone_number') ? $request->phone_number : $user_data->phone_number,
            'country_code' => $request->has('country_code') ? $request->country_code : $user_data->country_code,
            'country_code_text' => $request->has('country_code_text') ? $request->country_code_text : $user_data->country_code_text,
            'is_buyer' => $request->has('is_buyer') ? $request->is_buyer : $user_data->is_buyer,
            'is_2fa_enabled' => $request->has('is_2fa_enabled') ? $request->is_2fa_enabled : $user_data->is_2fa_enabled,
            'profile_publicly_visible' => $request->has('profile_publicly_visible') ? $request->profile_publicly_visible : $user_data->profile_publicly_visible
        ]);

        UserDetails::where('user_id', $request->user()->id)->update([
            'user_intro' => isset($request->details['user_intro']) ? $request->details['user_intro'] : $user_data->details->user_intro,
            'user_description' => isset($request->details['user_description']) ? $request->details['user_description'] : $user_data->details->user_description,
            'user_average_response_time' => isset($request->details['user_average_response_time']) ? $request->details['user_average_response_time'] : $user_data->details->user_average_response_time,
            'user_languages' => isset($request->details['user_languages']) ? json_encode($request->details['user_languages']) : $user_data->details->user_languages,
            'user_skills' => isset($request->details['user_skills']) ? json_encode($request->details['user_skills']) : $user_data->details->user_skills,
            'user_education' => isset($request->details['user_education']) ? $request->details['user_education'] : $user_data->details->user_education,
            'cnic' => isset($request->details['cnic']) ? $request->details['cnic'] : $user_data->details->cnic,
            'location' => isset($request->details['location']) ? $request->details['location'] : $user_data->details->location,
            'city' => isset($request->details['city']) ? $request->details['city'] : $user_data->details->city,
            'country' => isset($request->details['country']) ? $request->details['country'] : $user_data->details->country,
            'facebook_link' => isset($request->details['facebook_link']) ? $request->details['facebook_link'] : $user_data->details->facebook_link,
            'linkedin_link' => isset($request->details['linkedin_link']) ? $request->details['linkedin_link'] : $user_data->details->linkedin_link,
            'twitter_link' => isset($request->details['twitter_link']) ? $request->details['twitter_link'] : $user_data->details->twitter_link,
            'github_link' => isset($request->details['github_link']) ? $request->details['github_link'] : $user_data->details->github_link
        ]);

        UserAccount::where('user_id', $request->user()->id)->update([
            'accept_custom_offers' => isset($request->account['accept_custom_offers']) ? $request->account['accept_custom_offers'] : $user_data->account->accept_custom_offers
        ]);

        if ($result) {
            $new_data = User::where('id', $request->user()->id)->with('account', 'details', 'membershipplandetails', 'paymentMethods', 'orderRatingAsSeller')->first();
            return response()->json(['data' => new UserProfileResource($new_data)], 200);
        } else {
            return response()->json(['message' => "Error Occured!"], 500);
        }
    }

    public function updateUserProfileImage(Request $request)
    {
        $user_data = User::where('id', $request->user()->id)->with('details')->first();

        $oldImageURL = $user_data->profile_img;

        $profile_image = null;
        if (request('profile_image')) {
            $profile_image = request('profile_image')->store('profileimage');
            Storage::delete($oldImageURL);
        }

        $result = User::where('id', $request->user()->id)->update([
            'profile_img' => $profile_image ? $profile_image : $user_data->profile_img,
        ]);

        if ($result) {
            $new_data = User::where('id', $request->user()->id)->with('account', 'details', 'membershipplandetails', 'paymentMethods', 'orderRatingAsSeller')->first();
            return response()->json(['data' => new UserProfileResource($new_data)], 200);
        } else {
            return response()->json(['message' => "Error Occured!"], 500);
        }
    }

    public function searchPerson(Request $request)
    {
        $request->validate([
            'email' => 'required'
        ]);
        $user = User::where('email', $request->email)->with('details')->first();
        if (!$user) {
            return response()->json(['message' => 'User Not Found!'], 500);
        } else {
            return response()->json(['data' => new SearchUserResource($user)], 200);
        }
    }

    public function deleteUserAccount(Request $request)
    {
        $result = $request->user()->delete();
        if ($result) {
            return response()->json(['data' => 'Account Deleted!'], 200);
        } else {
            return response()->json(['data' => 'Error Occured!'], 500);
        }
    }

    public function getUserSecurityQuestions(Request $request)
    {
        $questions = UserSecurityQuestion::where('user_id', $request->user()->id)->get();
        return response()->json(['data' => UserSecurityQuestionResource::collection($questions)], 200);
    }

    public function addUserSecurityQuestion(Request $request)
    {
        $request->validate([
            'question' => 'required',
            'answer' => 'required'
        ]);
        $user_id = $request->user()->id;
        UserSecurityQuestion::create([
            'user_id' => $user_id,
            'question' => $request->question,
            'answer' => $request->answer
        ]);
        return response()->json(['data' => 'Added Successfully!'], 200);
    }

    public function addUserSecurityQuestions(Request $request)
    {
        // $request->validate([
        //     'question' => 'required',
        //     'answer' => 'required'
        // ]);
        foreach ($request->toArray() as $question) {
            UserSecurityQuestion::create([
                'user_id' => $request->user()->id,
                'question' => $question['question'],
                'answer' => $question['answer']
            ]);
        }
        $questions = UserSecurityQuestion::where('user_id', $request->user()->id)->get();
        return response()->json(['data' => UserSecurityQuestionResource::collection($questions)], 200);
    }

    public function updateUserSecurityQuestion(Request $request, UserSecurityQuestion $question)
    {
        $request->validate([
            'question' => 'required',
            'answer' => 'required'
        ]);
        $question->update([
            'question' => $request->question,
            'answer' => $request->answer
        ]);
        return response()->json(['data' => 'Updated Successfully!'], 200);
    }

    public function updateUserSecurityQuestions(Request $request)
    {
        // $request->validate([
        //     'question' => 'required',
        //     'answer' => 'required'
        // ]);
        // $question->update([
        //     'question' => $request->question,
        //     'answer' => $request->answer
        // ]);
        foreach ($request->toArray() as $question) {
            UserSecurityQuestion::where('id', $question['id'])->update([
                'question' => $question['question'],
                'answer' => $question['answer']
            ]);
        }
        // return response()->json(['data' => $request->toArray()], 200);
        return response()->json(['data' => 'Updated Successfully!'], 200);
    }

    public function deleteUserSecurityQuestion(UserSecurityQuestion $question)
    {
        $question->delete();
        return response()->json(['data' => 'Deleted Successfully!'], 200);
    }

    public function changeUserRole(Request $request)
    {
        // return response()->json(['data' => 'Error Occured!'], 500);

        $role = 'seller';
        $is_buyer = false;
        if ($request->has('role')) {
            if ($request->role == 'seller') {
                $role = 'seller';
                $is_buyer = false;
            } else if ($request->role == 'buyer') {
                $role = 'buyer';
                $is_buyer = true;
            } else {
                return response()->json(['data' => 'Incorrect User Role!'], 500);
            }
            $result = $request->user()->update([
                'is_buyer' => $is_buyer,
                'role' => $role
            ]);
            if ($result) {
                return response()->json(['data' => 'Role Updated Successfully!'], 200);
            } else {
                return response()->json(['data' => 'Error Occured!'], 500);
            }
        } else {
            return response()->json(['data' => 'Error Occured!'], 500);
        }
    }
}
