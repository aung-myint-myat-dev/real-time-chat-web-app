<?php

namespace App\Actions\Chat;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;

class MarkConversationAsReadAction
{
    public function __construct(
        private BroadcastConversationUpdateAction $broadcastConversationUpdate,
    ) {}

    public function execute(Conversation $conversation, User $user, ?int $messageId = null): void
    {
        $messageId ??= Message::query()
            ->where('conversation_id', $conversation->id)
            ->latest('id')
            ->value('id');

        if (! $messageId) {
            return;
        }

        $conversation->conversationUsers()
            ->where('user_id', $user->id)
            ->update(['last_read_message_id' => $messageId]);

        $this->broadcastConversationUpdate->execute($conversation);
    }
}
