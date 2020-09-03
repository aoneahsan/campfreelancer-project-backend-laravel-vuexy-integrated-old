<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\MainController;

use App\User;
use Authy\AuthyApi;

class AuthyVerifyController extends MainController
{
    public function index()
    {
        return view('auth.verify');
    }
    public function verify(Request $request)
    {
        try {
            $data = $request->validate([
                'verification_code' => ['required', 'numeric'],
            ]);
            $authy_api = new AuthyApi(env('AUTHY_SECRET'));
            $res = $authy_api->verifyToken(auth()->user()->authy_id, $data['verification_code']);
            if ($res->bodyvar("success")) {
                \session(['isVerified' => true]);
                return redirect()->route('home');
            }
            return back()->with(['error' => $res->errors()->message]);
        } catch (\Throwable $th) {
            return back()->with(['error' => $th->getMessage()]);
        }
    }

    public function resendCode(Request $request)
    {
        // dd($request->toArray());
        $authy_api = new AuthyApi(env('AUTHY_SECRET'));
        $result = $authy_api->requestSms($request->user_id);
        // dd($result, $request->authy_id, $authy_api);
        if ($result) {
            return redirect()->back()->with('code-send', 'Code Send Successfully!');
        } else {
            return redirect()->back()->with('code-send-fail', 'Error Occured!');
        }        
    }
}