<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\Notification\NotificationResource;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;

class ApiUserNotificationController extends Controller
{
    public function index(Request $request)
    {
        $readNo = $request->user()->readNotifications;
        $unreadNo = $request->user()->unReadNotifications;
        $unreadNo_Count = count($unreadNo);
        return [
            'read' => NotificationResource::collection($readNo),
            'unread' => NotificationResource::collection($unreadNo),
            'unread_count' => $unreadNo_Count
        ];
    }

    public function markNotificationAsRead(Request $request)
    {
        $request->user()->notifications->where('id', $request->id)->markAsRead();
        return response()->json(['data' => "Done!"], 200);
    }
}
