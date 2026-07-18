<?php

namespace App\Actions\Message;

use App\Events\Message\MessageSent;
use App\Models\Message;

class SendMessageAction
{

    public function execute(array $data): Message
    {
        $message = Message::create([
            'conversation_id' => $data['conversation_id'],
            'user_id' => $data['user_id'],
            'body' => $data['body'],
            'reply_message_id' => $data['reply_message_id'],
            'type' => $data['type']
        ]);

        broadcast(new MessageSent($message))->toOthers();

        return $message->load('user');
    }
}
