<?php

use ChatModule\Http\Controllers\ChannelController;
use ChatModule\Http\Controllers\ChatController;
use ChatModule\Models\Channel;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;


Route::get('/', [ChatController::class, 'index'])->name('chat.index');

Route::post('channel/create', [ChannelController::class, 'store'])
    ->name('channelStore');
//Route::get('channel/{id}', [ChannelController::class, 'show'])
//    ->name('channelDetail');
//Route::delete('channel/{id}', [ChannelController::class, 'userDelete'])
//    ->name('channelUserDelete');


Route::get('chat/messages', [ChatController::class, 'chatMessages'])
    ->name('chatMessage');
Route::post('chat/messages', [ChatController::class, 'chatMessageStore'])
    ->name('chatMessageStore');


// Broadcast routes
Broadcast::channel('Chat.Channel.{channel_id}', function (User $user, $id) {
    return Channel::where('id', $id)->whereHas('users', function ($q) use ($user) {
        $q->where('id', $user->id);
    })->exists();
});

