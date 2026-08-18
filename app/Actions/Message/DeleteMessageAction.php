<?php

namespace App\Actions\Message;

use App\Events\Message\MessageDeleted;
use App\Models\Message;

class DeleteMessageAction
{
    public function execute(
        Message $message
    ): void {
        $messageId = $message->id;
        $conversationId = $message->conversation_id;
        broadcast(new MessageDeleted(messageId: $messageId, conversationId: $conversationId))->toOthers();
        $message->delete();
    }
}