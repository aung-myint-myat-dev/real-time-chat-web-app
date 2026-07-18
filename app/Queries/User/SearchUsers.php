<?php

namespace App\Queries\User;

use App\Models\User;
use App\Models\Conversation;

class SearchUsers
{
    public function execute(string $search, int $currentUserId)
    {
        return User::query()
            ->where('username', $search)
            ->where('id', '!=', $currentUserId)
            ->get()
            ->map(function ($user) use ($currentUserId) {

                $conversation = Conversation::query()
                    ->where('type', 'private')
                    ->whereHas('users', function ($query) use ($currentUserId) {
                        $query->where('users.id', $currentUserId);
                    })
                    ->whereHas('users', function ($query) use ($user) {
                        $query->where('users.id', $user->id);
                    })
                    ->first();

                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'username' => $user->username,
                    'avatar' => $user->avatar,
                    'conversation_id' => $conversation?->id,
                ];
            });
    }
}
