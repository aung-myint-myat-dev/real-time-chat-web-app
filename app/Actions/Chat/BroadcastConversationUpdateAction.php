<?php

namespace App\Actions\Chat;

use App\Events\Message\ConversationUpdated;
use App\Models\Conversation;
use App\Support\ConversationSidebarFormatter;

class BroadcastConversationUpdateAction
{
    public function __construct(
        private ConversationSidebarFormatter $formatter,
    ) {}

    public function execute(Conversation $conversation): void
    {
        $conversation->loadMissing('members');

        foreach ($conversation->members as $member) {
            broadcast(new ConversationUpdated(
                userId: $member->id,
                conversation: $this->formatter->format($conversation, $member),
            ));
        }
    }
}
