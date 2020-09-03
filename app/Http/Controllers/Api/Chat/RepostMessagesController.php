<?php

namespace App\Http\Controllers\Api\Chat;

use App\Http\Controllers\Controller;
use App\Model\Chat\Message;
use App\Model\Chat\ReportMessage;
use Illuminate\Http\Request;

class RepostMessagesController extends Controller
{
    public function index()
    {
        //
    }

    public function store(Request $request)
    {
        $user_id = $request->user()->id;
        $report_spam_message = ReportMessage::create([
            'user_id' => $user_id,
            'message_sender_id' => !!$request->has('message_sender_id') ? $request->message_sender_id : null,
            'message_id' => !!$request->has('message_id') ? $request->message_id : null,
            'reason' => !!$request->has('reason') ? $request->reason : null,
            'is_spammed' => !!$request->has('is_spammed') ? $request->is_spammed : null,
            'type' => !!$request->has('type') ? $request->type : null
        ]);

        $is_reported = false;
        $is_spammed = false;

        if ($request->type == 'report') {
            $is_reported = true;
        }
        else if ($request->type == 'spam') {
            $is_spammed = true;
        }

        Message::where('id', $request->message_id)->update([
            'is_reported' => $is_reported,
            'is_spammed' => $is_spammed
        ]);

        if ($report_spam_message) {
            return response()->json(['data' => 'Added!'], 200);
        } else {
            return response()->json(['message' => 'Error Occured!'], 500);
        }
    }

    public function show(ReportMessage $reportMessage)
    {
        //
    }

    public function update(Request $request, ReportMessage $reportMessage)
    {
        //
    }

    public function destroy(Request $request, $messageID)
    {
        $report_spam_message = ReportMessage::where('message_id', $messageID)->delete();

        Message::where('id', $messageID)->update([
            'is_reported' => false,
            'is_spammed' => false
        ]);
        
        if ($report_spam_message) {
            return response()->json(['data' => 'Deleted!'], 200);
        } else {
            return response()->json(['message' => 'Error Occured!'], 500);
        }
    }
}
