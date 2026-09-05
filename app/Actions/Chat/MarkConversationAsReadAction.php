<?php

namespace App\Actions\Chat;

use App\Events\Message\MessagesRead;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class MarkConversationAsReadAction
{
    public function __construct(
        private BroadcastConversationUpdateAction $broadcastConversationUpdate,
    ) {}

    public function execute(Conversation $conversation, User $user, ?int $upToMessageId = null): void
    {
        if ($upToMessageId && ! $this->messageBelongsToConversation($conversation, $upToMessageId)) {
            return;
        }

        $unreadMessageIds = Message::query()
            ->where('conversation_id', $conversation->id)
            ->where('user_id', '!=', $user->id)
            ->whereDoesntHave('reads', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->when($upToMessageId, fn ($query) => $query->where('id', '<=', $upToMessageId))
            ->pluck('id');

        if ($unreadMessageIds->isEmpty()) {
            $this->advanceLastReadMessageId($conversation, $user, $upToMessageId);

            return;
        }

        $now = now();
        $insertData = $unreadMessageIds->map(fn ($id) => [
            'user_id' => $user->id,
            'message_id' => $id,
            'read_at' => $now,
            'created_at' => $now,
            'updated_at' => $now,
        ])->toArray();

        DB::table('message_reads')->insertOrIgnore($insertData);

        $lastReadId = max(
            array_filter([$unreadMessageIds->max(), $upToMessageId])
        );

        $this->advanceLastReadMessageId($conversation, $user, $lastReadId);

        broadcast(new MessagesRead(
            conversationId: $conversation->id,
            readerId: $user->id,
            messageIds: $unreadMessageIds->all(),
            readAt: $now->toIso8601String(),
        ))->toOthers();

        $this->broadcastConversationUpdate->execute($conversation);
    }

    private function messageBelongsToConversation(Conversation $conversation, int $messageId): bool
    {
        return Message::query()
            ->where('conversation_id', $conversation->id)
            ->where('id', $messageId)
            ->exists();
    }

    private function advanceLastReadMessageId(Conversation $conversation, User $user, ?int $messageId): void
    {
        if (! $messageId) {
            return;
        }

        $currentLastRead = $conversation->conversationUsers()
            ->where('user_id', $user->id)
            ->value('last_read_message_id');

        if ($currentLastRead && $messageId <= $currentLastRead) {
            return;
        }

        $conversation->conversationUsers()
            ->where('user_id', $user->id)
            ->update(['last_read_message_id' => $messageId]);
    }
}
