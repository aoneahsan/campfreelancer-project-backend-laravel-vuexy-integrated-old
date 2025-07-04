<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

/*
|--------------------------------------------------------------------------
// Chat Module Channels
|--------------------------------------------------------------------------
*/
Broadcast::channel('messageSendChannel', function () {
    return true;
});
Broadcast::channel('newChatChannel', function () {
    return true;
});

/*
|--------------------------------------------------------------------------
// Orders Module Channel
|--------------------------------------------------------------------------
*/
Broadcast::channel('orderChatChannel', function () {
    return true;
});
