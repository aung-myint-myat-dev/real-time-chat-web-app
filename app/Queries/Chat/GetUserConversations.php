<?php

namespace App\Queries\Chat;

use App\Models\Conversation;
use App\Models\User;

class GetUserConversations
{
    public function execute(User $user)
    {
        return Conversation::query()
            ->whereHas('users', function ($query) use ($user) {
                $query->where('users.id', $user->id);
            })
            ->with([
                'users' => function ($query) use ($user) {
                    $query
                        ->where('users.id', '!=', $user->id)
                        ->select(
                            'users.id',
                            'users.name',
                            'users.username',
                            'users.avatar',
                            'users.last_seen_at'
                        );
                }
            ])
            ->latest()
            ->get();
    }
}
