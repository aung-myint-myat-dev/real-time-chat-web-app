<?php

namespace App\Actions\Message;

use App\Actions\Chat\MarkConversationAsReadAction;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class MarkAsReadMessageAction
{
    public function __construct(
        private MarkConversationAsReadAction $markConversationAsRead,
    ) {}

    public function execute(Message $message): void
    {
        if ($message->user_id === Auth::id()) {
            return;
        }

        $message->loadMissing('conversation');

        $this->markConversationAsRead->execute(
            $message->conversation,
            Auth::user(),
            $message->id,
        );
    }
}
