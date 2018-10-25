<?php

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
use Illuminate\Support\Facades\Log;

Broadcast::channel('d_pm.{id}', function ($user, $id) {
	// Log::info('Channels route::d_pm.id: status='.(int) $user->id === (int) $id);
    return (int) $user->id === (int) $id;
});

Broadcast::channel('pub-channel', function ($user) {
	// Log::info('Channels route::d_pm.id: status='.(int) $user->id === (int) $id);
    // return (int) $user->id === (int) $id;
    return true;
});
/*
Broadcast::channel('d_pm.{userId}', function ($user, $userId) {
    return $user->id === Order::findOrNew($user)->user_id;
});
*/