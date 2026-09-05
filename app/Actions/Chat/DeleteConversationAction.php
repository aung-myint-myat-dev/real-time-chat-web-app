<?php

namespace App\Actions\Chat;

use App\Events\Conversation\ConversationDeleted;
use App\Models\Conversation;
use Illuminate\Support\Facades\DB;

class DeleteConversationAction
{
    public function execute(Conversation $conversation): void
    {
        $conversation->loadMissing('members');
        $memberIds = $conversation->members->pluck('id')->all();
        $conversationId = $conversation->id;

        DB::transaction(function () use ($conversation, $memberIds, $conversationId) {
            $conversation->conversationUsers()->update([
                'last_read_message_id' => null,
            ]);

            $conversation->delete();

            DB::afterCommit(function () use ($memberIds, $conversationId) {
                foreach ($memberIds as $memberId) {
                    broadcast(new ConversationDeleted(
                        userId: $memberId,
                        conversationId: $conversationId,
                    ))->toOthers();
                }
            });
        });
    }
}
