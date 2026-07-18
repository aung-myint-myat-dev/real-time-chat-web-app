<?php

namespace App\Actions\Chat;

use App\Models\Conversation;
use App\Models\ConversationUser;
use App\Models\Message;

class StartConversationAction
{

    public function execute(array $data): Conversation
    {
        $user = auth()->user();

        $conversation = Conversation::create([
            'created_by' => $user->id,
            'type' => 'private',
        ]);

        $conversation->conversationUsers()->createMany([
            [
                'user_id' => $user->id,
                'joined_at' => now(),
                'is_muted' => false,
                'is_pinned' => false,
            ],
            [
                'user_id' => $data['other_user_id'],
                'joined_at' => now(),
                'is_muted' => false,
                'is_pinned' => false,
            ],
        ]);

        Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => $user->id,
            'body' => 'Hi'
        ]);

        return $conversation;
    }
}
