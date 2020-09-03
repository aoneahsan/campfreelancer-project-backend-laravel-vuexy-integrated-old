<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\MainController;
use Illuminate\Validation\ValidationException;

// Facades
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

// Models
use App\User;
use App\Model\User\UserAccount;
use App\Model\User\UserDetails;

// Resources
use App\Http\Resources\User\UserLoginResource;
use Authy\AuthyApi;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

class ApiAuthController extends MainController
{

    public function loginApi(Request $request)
    {
        $request->validate([
            'email' => 'required',
            // 'phone_number' => 'required',
            'password' => 'required|min:6'
        ]);

        // from frontend get username or email in "email" key.

        $user = User::where('email', $request->email)->orWhere('username', $request->email)->first();
        // $user = User::where('phone_number', $request->phone_number)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => ['Invalid Email/Username.']
            ]);
        }

        if (!Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'password' => ['Invalid Password.']
            ]);
        }

        $newUser = User::where('id', $user->id)->first();

        return response()->json(['data' => new UserLoginResource($newUser)], 200);
    }

    public function registerApi(Request $request)
    {
        $request->validate([
            'username' => ['required', 'alpha_dash', 'unique:users', 'max:20'],
            // 'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6']
            // 'phone_number' => ['required', 'string', 'unique:users'],
            // 'country_code' => ['required']
            // 'role' => ['required', 'string']
        ]);

        $new_user_role = 'seller';
        if ($request->role) {
            $new_user_role = $request->role;
        }

        $user = User::create([
            'username' => $request->username,
            'name' => $request->has('name') ? $request->name : $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone_number' => $request->phone_number,
            'country_code' => $request->country_code,
            'country_code_text' => $request->country_code_text,
            'role' => $new_user_role,
            'seller_plan_id' => 1,
            'buyer_plan_id' => 2
        ]);

        UserDetails::create([
            'user_id' => $user->id
        ]);
        UserAccount::create([
            'user_id' => $user->id
        ]);

        $user->assignRole($new_user_role);

        $newUser = User::where('id', $user->id)->first();

        return response()->json(['data' => new UserLoginResource($newUser)], 200);
    }

    public function logoutApi(Request $request)
    {
        if (!!$request->user()->is_2fa_enabled) {
            $request->user()->update([
                'is_2fa_verified' => false
            ]);
        }
        // Auth::guard()->logout();
        $request->session()->flush();
        $request->user()->tokens()->delete();

        return response()->json(['data' => 'User Tokkens Deleted'], 200);
    }

    public function checkLoginStatus(Request $request)
    {
        if ($request->user()) {
            response()->json(['data' => 'Working!'], 200);
        }
        response()->json(['message' => 'Eror Occured!'], 500);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'currentpassword' => 'required|min:6',
            'newpassword' => 'required|min:6'
        ]);

        if (Hash::check($request->currentpassword, $request->user()->password)) {
            $request->user()->password = Hash::make($request->newpassword);
            $request->user()->setRememberToken(Str::random(60));
            $request->user()->save();
            event(new PasswordReset($request->user()));
            return response()->json(['data' => 'password Changed!'], 200);
        }
        throw ValidationException::withMessages([
            'password' => ['Current Password is not correct.']
        ]);
    }

    public function socialLoginHandle(Request $request)
    {
        $user = User::where('id', $request->user()->id)->first();
        if ($user) {
            return response()->json(['data' => new UserLoginResource($user)], 200);
        } else {
            return response()->json(['message' => 'User Not Found!'], 500);
        }
    }

    public function startTwoFactorAuth(Request $request)
    {
        $user_data = User::where('id', $request->user()->id)->first();
        $authy_api = new AuthyApi(env('AUTHY_SECRET'));
        if (!!$request->user()->authy_id) {
            $result = $authy_api->requestSms($request->user()->authy_id);
            if ($result) {
                return response()->json(['data' => 'Verification Code Send'], 200);
            } else {
                return response()->json(['message' => 'Error Occured!'], 500);
            }
            return response()->json(['data' => 'Two Factor Auth Status Changed Successfully'], 200);
        } else {
            $authy_user = $authy_api->registerUser($request->user()->email, $request->user()->phone_number, $request->user()->country_code);
            if ($authy_user) {
                $authy_id = $authy_user->id();
                $user_data->update([
                    'authy_id' => $authy_id
                ]);
                $result = $authy_api->requestSms($authy_id);
                if ($result) {
                    return response()->json(['data' => 'Verification Code Send'], 200);
                } else {
                    return response()->json(['message' => 'Error Occured!'], 500);
                }
            } else {
                return response()->json(['message' => 'Error Occured!'], 500);
            }
        }
    }

    public function verifyAccount(Request $request)
    {
        try {
            $data = $request->validate([
                'verification_code' => ['required', 'numeric']
            ]);
            $authy_api = new AuthyApi(env('AUTHY_SECRET'));
            $res = $authy_api->verifyToken($request->user()->authy_id, $data['verification_code']);
            if ($res->bodyvar("success")) {
                return response()->json(['data' => 'verified'], 200);
            }
            return response()->json(['message' => $res->errors()->message], 500);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()]);
        }
    }

    public function resendVerificationCode(Request $request)
    {
        $authy_api = new AuthyApi(env('AUTHY_SECRET'));
        $result = $authy_api->requestSms($request->user()->authy_id);
        if ($result) {
            return response()->json(['data', 'Code Send Successfully!'], 200);
        } else {
            return response()->json(['message' => 'Error Occured!'], 500);
        }
    }
}
