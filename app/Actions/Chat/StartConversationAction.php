<?php

namespace App\Actions\Chat;

use App\Actions\Chat\BroadcastConversationUpdateAction;
use App\Events\Conversation\ConversationCreated;
use App\Events\Message\MessageSent;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Support\Facades\DB;

class StartConversationAction
{
    public function __construct(
        private BroadcastConversationUpdateAction $broadcastConversationUpdate,
    ) {}

    public function execute(array $data): Conversation
    {
        $user = auth()->user();

        return DB::transaction(function () use ($user, $data) {

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

            $message = Message::create([
                'conversation_id' => $conversation->id,
                'user_id' => $user->id,
                'body' => 'Hi',
            ]);

            DB::afterCommit(function () use ($conversation, $message) {
                broadcast(new MessageSent($message));

                $conversation->load('users');

                broadcast(new ConversationCreated(
                    conversation: $conversation,
                ))->toOthers();

                $this->broadcastConversationUpdate->execute($conversation);
            });

            return $conversation;
        });
    }
}
