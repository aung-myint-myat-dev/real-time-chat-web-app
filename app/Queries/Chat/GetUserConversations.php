<?php

namespace App\Queries\Chat;

use App\Models\Conversation;
use App\Models\User;
use App\Support\ConversationSidebarFormatter;

class GetUserConversations
{
    public function __construct(
        private ConversationSidebarFormatter $formatter,
    ) {}

    public function execute(User $user): array
    {
        $conversations = Conversation::query()
            ->whereHas('users', fn ($query) => $query->where('users.id', $user->id))
            ->withMax('messages', 'created_at')
            ->orderByDesc('messages_max_created_at')
            ->orderByDesc('id')
            ->get();

        return $conversations
            ->map(fn (Conversation $conversation) => $this->formatter->format($conversation, $user))
            ->all();
    }
}
