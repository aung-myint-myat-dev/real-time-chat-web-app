<?php

use App\Models\Conversation;
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