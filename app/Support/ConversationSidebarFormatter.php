<?php

namespace App\Support;

use App\Models\Conversation;
use App\Models\ConversationUser;
use App\Models\Message;
use App\Models\User;

class ConversationSidebarFormatter
{
    public function format(Conversation $conversation, User $user): array
    {
        $conversation->loadMissing([
            'users' => fn ($query) => $query
                ->where('users.id', '!=', $user->id)
                ->select(
                    'users.id',
                    'users.name',
                    'users.username',
                    'users.avatar',
                    'users.last_seen_at',
                ),
            'latestMessage',
        ]);

        $lastMessage = $conversation->latestMessage;

        return [
            'id' => $conversation->id,
            'type' => $conversation->type,
            'name' => $conversation->name,
            'users' => $conversation->users,
            'last_message' => $lastMessage?->body,
            'last_message_at' => $lastMessage?->created_at?->toIso8601String(),
            'unread_count' => $this->unreadCount($conversation, $user),
        ];
    }

    public function unreadCount(Conversation $conversation, User $user): int
    {
        $lastReadMessageId = ConversationUser::query()
            ->where('conversation_id', $conversation->id)
            ->where('user_id', $user->id)
            ->value('last_read_message_id');

        return Message::query()
            ->where('conversation_id', $conversation->id)
            ->where('user_id', '!=', $user->id)
            ->when(
                $lastReadMessageId,
                fn ($query) => $query->where('id', '>', $lastReadMessageId),
            )
            ->count();
    }
}
