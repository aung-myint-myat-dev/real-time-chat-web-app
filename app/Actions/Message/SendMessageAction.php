<?php

namespace App\Actions\Message;

use App\Actions\Chat\BroadcastConversationUpdateAction;
use App\Events\Message\MessageSent;
use App\Models\Conversation;
use App\Models\Message;

class SendMessageAction
{
    public function __construct(
        private BroadcastConversationUpdateAction $broadcastConversationUpdate,
    ) {}

    public function execute(array $data): Message
    {
        $message = Message::create([
            'conversation_id' => $data['conversation_id'],
            'user_id' => $data['user_id'],
            'body' => $data['body'],
            'reply_message_id' => $data['reply_message_id'],
            'type' => $data['type'],
        ]);

        broadcast(new MessageSent($message))->toOthers();

        $conversation = Conversation::query()->findOrFail($message->conversation_id);
        $this->broadcastConversationUpdate->execute($conversation);

        return $message->load('user');
    }
}
