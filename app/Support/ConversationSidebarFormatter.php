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
        $lastReadMessageId = $this->lastReadMessageId($conversation, $user);

        return [
            'id' => $conversation->id,
            'type' => $conversation->type,
            'name' => $conversation->name,
            'users' => $conversation->users,
            'last_message' => $lastMessage?->body,
            'last_message_at' => $lastMessage?->created_at?->toIso8601String(),
            'unread_count' => $this->unreadCount($conversation, $user, $lastReadMessageId),
            'last_read_message_id' => $lastReadMessageId,
            'first_unread_message_id' => $this->firstUnreadMessageId($conversation, $user, $lastReadMessageId),
        ];
    }

    public function lastReadMessageId(Conversation $conversation, User $user): ?int
    {
        $id = ConversationUser::query()
            ->where('conversation_id', $conversation->id)
            ->where('user_id', $user->id)
            ->value('last_read_message_id');

        return $id ? (int) $id : null;
    }

    public function firstUnreadMessageId(Conversation $conversation, User $user, ?int $lastReadMessageId = null): ?int
    {
        $lastReadMessageId ??= $this->lastReadMessageId($conversation, $user);

        $id = Message::query()
            ->where('conversation_id', $conversation->id)
            ->where('user_id', '!=', $user->id)
            ->when(
                $lastReadMessageId,
                fn ($query) => $query->where('id', '>', $lastReadMessageId),
            )
            ->orderBy('id')
            ->value('id');

        return $id ? (int) $id : null;
    }

    public function unreadCount(Conversation $conversation, User $user, ?int $lastReadMessageId = null): int
    {
        $lastReadMessageId ??= $this->lastReadMessageId($conversation, $user);

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
