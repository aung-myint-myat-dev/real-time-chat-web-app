<?php

namespace App\Actions\Message;

use App\Events\Message\MessageEdited;
use App\Models\Message;

class UpdateMessageAction
{
    public function execute(
        Message $message, 
        string $body
    ): Message {
        if($message->body === $body) {
            return $message;
        }
        $message->update([
            'body' => $body,
            'edited_at' => now(),
        ]);
        broadcast(new MessageEdited($message))->toOthers();
        return $message;
    }
}