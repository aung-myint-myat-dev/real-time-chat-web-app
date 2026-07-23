<?php

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chats.{conversationId}', function ($user, $conversationId) {
    return Conversation::query()
        ->whereKey($conversationId)
        ->whereHas('users', fn($q) => $q->where('users.id', $user->id))
        ->exists();
});

Broadcast::channel('users.{userId}', function ($user, $userId) {
    return $user->id == $userId;
});

Broadcast::channel('online', function (User $user) {

    return [
        'id' => $user->id,
        'name' => $user->name
    ];
});
