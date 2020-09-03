<?php

namespace App\Http\Controllers\Api\Chat;

use App\Events\MessageSendEvent;
use App\Events\Order\UserNotificationEvent;
use App\Http\Controllers\Controller;
use App\Http\Resources\Chat\UserChatMessagesResource;
use App\Model\Chat\ChatUser;
use App\Model\Chat\Message;
use App\Notifications\UserNotification;
use App\User;
use Illuminate\Http\Request;

class MessagesController extends Controller
{
    public function index(Request $request, $reciver_id)
    {
        $user_id = $request->user()->id;
        $chatExists = ChatUser::where('user_id', $user_id)->where('reciver_id', $reciver_id)->first();
        if ($chatExists) {
            if ($user_id == $reciver_id) {
                Message::where('user_id', $reciver_id)->where('reciver_id', $user_id)->update([
                    'is_read' => true
                ]);
            }
            $messages = Message::where(function ($query) use ($user_id, $reciver_id) {
                $query->where('user_id', $user_id)->where('reciver_id', $reciver_id);
            })->orWhere(function ($query) use ($user_id, $reciver_id) {
                $query->where('user_id', $reciver_id)->where('reciver_id', $user_id);
            })->limit(100)->get();
            Message::where('user_id', $reciver_id)->where('reciver_id', $user_id)->update([
                'is_read' => true
            ]);
            return response()->json(['data' => UserChatMessagesResource::collection($messages)], 200);
        } else {
            return response()->json(['message' => "Not Found!"], 404);
        }
    }

    public function store(Request $request, $reciver_id)
    {
        $sender_id = $request->user()->id;

        $file_name = null;
        if (!!$request->has('file')) {
            if ($request->hasFile('file')) {
                $file_name = $request->file('file')->store('messages');
            }
        }

        $message = Message::create([
            'user_id' => $sender_id,
            'reciver_id' => $reciver_id,
            'message' => !!$request->has('message') ? $request->message : null,
            'custom_offer_data' => !!$request->has('custom_offer_data') ? json_encode($request->custom_offer_data) : null,
            'file_name' => $file_name,
            'type' => !!$request->has('type') ? $request->type : null,
            'file_type' => !!$request->has('file_type') ? $request->file_type : null
        ]);

        $chat_user = ChatUser::where('user_id', $reciver_id)->where('reciver_id', $sender_id)->first();
        $chat_user->update([
            'latest_message' => !!$request->has('message') ? $request->message : $chat_user->latest_message,
            'message_type' => !!$request->has('type') ? $request->type : $chat_user->message_type,
            'message_sender_id' => $sender_id,
            'message_send_at' => $message->created_at
        ]);


        if ($message) {
            $messageResource = new UserChatMessagesResource($message);
            try {
                $reciver = User::where('id', $reciver_id)->first();
                // $reciver->notify(new UserNotification($messageResource, 'new_chat_message', 'any', $reciver->id));
                // broadcast(new UserNotificationEvent($messageResource, 'new_chat_message', 'any', $reciver->id))->toOthers();
                broadcast(new MessageSendEvent($messageResource, $reciver_id, $sender_id))->toOthers();
            } catch (\Throwable $th) {
            }
            return response()->json(['data' => $messageResource], 200);
        } else {
            return response()->json(['message' => 'Error Occured!'], 500);
        }
    }

    public function markAsRead(Request $request, $message_id)
    {
        Message::where('id', $message_id)->update([
            'is_read' => true
        ]);

        return response()->json(['data' => "Message Read Successfully!"], 200);
    }

    public function markAsReadALlMessages(Request $request, $reciver_id)
    {
        $user_id = $request->user()->id;
        Message::where('user_id', $user_id)->where('reciver_id', $reciver_id)->update([
            'is_read' => true
        ]);

        return response()->json(['data' => "Message Read Successfully!"], 200);
    }

    public function update(Request $request, $message_id)
    {
        // return response()->json(['message' => $request->custom_offer_status], 500);

        $message = Message::where('id', $message_id)->first();
        $message->update([
            'custom_offer_status' => $request->has('custom_offer_status') ? $request->custom_offer_status : false
        ]);
        $message->save();
        

        if ($message) {
            return response()->json(['data' => "Message Updated Successfully!"], 200);
        } else {
            return response()->json(['message' => 'Error Occured, while updating message custom_offer_status status.'], 500);
        }
    }

    public function destroy(Message $message)
    {
        //
    }
}
