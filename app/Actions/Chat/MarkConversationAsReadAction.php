<?php

namespace App\Actions\Chat;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class MarkConversationAsReadAction
{
    public function __construct(
        private BroadcastConversationUpdateAction $broadcastConversationUpdate,
    ) {}

    public function execute(Conversation $conversation, User $user): void
    {
        /**
         * Retriving last message id form conversation
         */
        $lastMessageId = Message::query()
        ->where('conversation_id', $conversation->id)
        ->latest('id')
        ->value('id');

        /**
         * Retriving all unread messages from message via reads relations
         */
        $unreadMessageIds = Message::query()
        ->where('conversation_id', $conversation->id)
        ->whereDoesntHave('reads', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })
        ->pluck('id'); // this return collection that cant be serialized into array

        /**
         * If all messages are already read, return
         */
        if($unreadMessageIds->isEmpty()) {
            return;
        }

        /**
         * Looping unread messages to store
         */
        $insertData = $unreadMessageIds->map(fn ($id) => [
            'user_id' => $user->id,
            'message_id' => $id,
            'created_at' => now(),
            'updated_at' => now(),
        ])->toArray();

        DB::table('message_reads')->insertOrIgnore($insertData);

        /**
         * Update conversation's last read message id.
         */
        $conversation->conversationUsers()
        ->where('user_id', $user->id)
        ->update(['last_read_message_id' => $lastMessageId]);

        /**
         * Broadcasting conversation update event
         */
        $this->broadcastConversationUpdate->execute($conversation);
    }
}
