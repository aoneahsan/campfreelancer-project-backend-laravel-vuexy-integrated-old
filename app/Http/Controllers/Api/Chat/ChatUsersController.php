<?php

namespace App\Http\Controllers\Api\Chat;

use App\Events\NewChatEvent;
use App\Http\Controllers\Controller;
use App\Http\Resources\Chat\ChatUsersResource;
use App\Http\Resources\Chat\OtherUserDataResource;
use App\Model\Chat\ChatUser;
use App\Model\Chat\Message;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChatUsersController extends Controller
{
    public function index(Request $request)
    {
        $user_id = $request->user()->id;
        // $chat_users = DB::select("SELECT chat_users.id, chat_users.is_favorite, chat_users.is_archived, chat_users.is_spammed, chat_users.label_type, users.id as user_id, users.name as user_name, users.email, users.profile_img, messages.message, messages.type, messages.user_id  FROM 
        // (SELECT MAX(id) AS id
        //          FROM messages 
        //          WHERE $user_id IN (user_id,reciver_id)
        //          GROUP BY IF ($user_id = user_id,reciver_id,user_id)) AS latest
        // LEFT JOIN messages ON latest.id = messages.id AND $user_id IN (messages.user_id, messages.reciver_id)
        // Left Join chat_users on IF($user_id = messages.user_id,messages.reciver_id,messages.user_id) = IF($user_id = chat_users.user_id,chat_users.reciver_id,chat_users.user_id)
        // Left join users on users.id = IF($user_id = chat_users.user_id,chat_users.reciver_id,chat_users.user_id)
        // GROUP BY IF ($user_id = messages.user_id,messages.reciver_id,messages.user_id)");
        // return response()->json(['data' => ChatUsersResource::collection($chat_users)], 200);
        $chat_users = ChatUser::where('user_id', $user_id)->get();
        $chat_users = ChatUser::select(
            'chat_users.*',
            DB::raw("(SELECT COUNT(*) FROM `messages` WHERE is_read = 0 and user_id = chat_users.reciver_id and reciver_id = $user_id) as unread_count", "(SELECT * FROM `users` WHERE id = chat_users.reciver_id) as reciver")
        )->where('chat_users.user_id', $user_id)->get();
        // return response()->json(['data' => $chat_users], 200);
        return response()->json(['data' => ChatUsersResource::collection($chat_users)], 200);
    }

    public function getChatUserData($chatUser)
    {
        $user = User::where('id', $chatUser)->orWhere('username', $chatUser)->first();
        if ($user) {
            return response()->json(['data' => new OtherUserDataResource($user)], 200);
        } else {
            return response()->json(['message' => 'User Not Found!'], 404);
        }
    }

    public function store(Request $request)
    {
        $user_id = $request->user()->id;
        // $reciver = User::where('id', $request->reciver_id)->orWhere('username', $request->reciver_id)->first();
        $reciver = User::where('id', $request->reciver_id)->first();
        $alreadyExists = ChatUser::where('user_id', $user_id)->where('reciver_id', $reciver->id)->first();
        if ($alreadyExists) {
            return response()->json(['data' => 'Already Exists!'], 200);
        }
        
        if (!$reciver) {
            return response()->json(['message' => 'No Reciver User found while starting chat!'], 404);
        }

        if ($reciver->id == $user_id) {
            return response()->json(['message' => "Can't Start Chat with your self."], 404);
        }

        ChatUser::create([
            'user_id' => $user_id,
            'reciver_id' => $reciver->id,
            'is_favorite' => !!$request->has('is_favorite') ? $request->is_favorite : null,
            'is_archived' => !!$request->has('is_archived') ? $request->is_archived : null,
            'is_spammed' => !!$request->has('is_spammed') ? $request->is_spammed : null,
            'label_type' => !!$request->has('label_type') ? $request->label_type : null
        ]);
        $alreadyExists = ChatUser::where('user_id', $reciver->id)->where('reciver_id', $user_id)->first();
        if ($alreadyExists) {
            $chat_user = true;
        } else {
            $chat_user = ChatUser::create([
                'user_id' => $reciver->id,
                'reciver_id' => $user_id,
                'is_favorite' => !!$request->has('is_favorite') ? $request->is_favorite : null,
                'is_archived' => !!$request->has('is_archived') ? $request->is_archived : null,
                'is_spammed' => !!$request->has('is_spammed') ? $request->is_spammed : null,
                'label_type' => !!$request->has('label_type') ? $request->label_type : null
            ]);
            try {
                broadcast(new NewChatEvent(new ChatUsersResource($chat_user), $reciver->id))->toOthers();
            } catch (\Throwable $th) {
            }
        }
        if ($chat_user) {
            return response()->json(['data' => $reciver], 200);
        } else {
            return response()->json(['message' => 'Error Occured!'], 500);
        }
    }

    public function show(ChatUser $chatUser)
    {
        //
    }

    public function update(Request $request, $chatUser)
    {
        $user_id = $request->user()->id;
        $chat_user = ChatUser::where('user_id', $user_id)->where('reciver_id', $chatUser)->first();

        $result = $chat_user->update([
            'is_favorite' => !!$request->has('is_favorite') ? $request->is_favorite : $chat_user->is_favorite,
            'is_archived' => !!$request->has('is_archived') ? $request->is_archived : $chat_user->is_archived,
            'is_spammed' => !!$request->has('is_spammed') ? $request->is_spammed : $chat_user->is_spammed,
            'label_type' => !!$request->has('label_type') ? $request->label_type : $chat_user->label_type
        ]);

        if ($result) {
            return response()->json(['data' => 'Updated!'], 200);
        } else {
            return response()->json(['message' => 'Error Occured!'], 500);
        }
    }

    public function destroy($chatUser)
    {
        $chat_user = ChatUser::where('id', $chatUser)->first();
        $user_id = $chat_user->user_id;
        $reciver_id = $chat_user->reciver_id;
        $result = $chat_user->delete();
        if ($result) {
            Message::where('user_id', $user_id)->where('reciver_id', $reciver_id)->delete();
            return response()->json(['data' => 'Deleted!'], 200);
        } else {
            return response()->json(['message' => 'Error Occured!'], 500);
        }
    }
}
