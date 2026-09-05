<?php

namespace App\Queries\Chat;

use App\Models\Conversation;
use App\Models\Message;
use App\Support\ConversationSidebarFormatter;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class GetConversationMessages
{
    public const PAGE_SIZE = 30;

    public function __construct(
        private ConversationSidebarFormatter $formatter,
    ) {}

    /**
     * @return array{messages: Collection<int, Message>, has_older: bool, has_newer: bool, first_unread_message_id: int|null}
     */
    public function execute(Conversation $conversation): array
    {
        $user = Auth::user();
        $beforeId = request()->integer('before_id') ?: null;
        $afterId = request()->integer('after_id') ?: null;
        $fromUnread = request()->boolean('from_unread');
        $lastReadId = $this->formatter->lastReadMessageId($conversation, $user);
        $firstUnreadId = $this->formatter->firstUnreadMessageId($conversation, $user, $lastReadId);

        $messagesQuery = fn () => $conversation->messages()
            ->select([
                'id',
                'conversation_id',
                'user_id',
                'body',
                'created_at',
                'edited_at',
            ])
            ->with([
                'user:id,name,avatar',
                'reads',
            ]);

        if ($beforeId) {
            $result = $this->pageBefore($messagesQuery(), $beforeId);
        } elseif ($afterId) {
            $result = $this->pageAfter($messagesQuery(), $afterId);
        } elseif ($fromUnread && $firstUnreadId) {
            $result = $this->pageFromUnread($messagesQuery(), $conversation, $firstUnreadId);
        } else {
            $result = $this->pageLatest($messagesQuery());
        }

        $result['messages']->each(function (Message $message) use ($lastReadId) {
            $message->setAttribute('viewer_last_read_message_id', $lastReadId);
        });

        $result['first_unread_message_id'] = $firstUnreadId;

        return $result;
    }

    /**
     * @return array{messages: Collection<int, Message>, has_older: bool, has_newer: bool}
     */
    private function pageFromUnread($query, Conversation $conversation, int $firstUnreadId): array
    {
        $messages = $query
            ->where('id', '>=', $firstUnreadId)
            ->orderBy('id')
            ->limit(self::PAGE_SIZE + 1)
            ->get();

        $hasNewer = $messages->count() > self::PAGE_SIZE;
        $messages = $messages->take(self::PAGE_SIZE)->values();

        return [
            'messages' => $messages,
            'has_older' => $conversation->messages()->where('id', '<', $firstUnreadId)->exists(),
            'has_newer' => $hasNewer,
        ];
    }

    /**
     * @return array{messages: Collection<int, Message>, has_older: bool, has_newer: bool}
     */
    private function pageLatest($query): array
    {
        $messages = $query
            ->orderByDesc('id')
            ->limit(self::PAGE_SIZE + 1)
            ->get();

        $hasOlder = $messages->count() > self::PAGE_SIZE;
        $messages = $messages->take(self::PAGE_SIZE)->reverse()->values();

        return [
            'messages' => $messages,
            'has_older' => $hasOlder,
            'has_newer' => false,
        ];
    }

    /**
     * @return array{messages: Collection<int, Message>, has_older: bool, has_newer: bool}
     */
    private function pageBefore($query, int $beforeId): array
    {
        $messages = $query
            ->where('id', '<', $beforeId)
            ->orderByDesc('id')
            ->limit(self::PAGE_SIZE + 1)
            ->get();

        $hasOlder = $messages->count() > self::PAGE_SIZE;
        $messages = $messages->take(self::PAGE_SIZE)->reverse()->values();

        return [
            'messages' => $messages,
            'has_older' => $hasOlder,
            'has_newer' => true,
        ];
    }

    /**
     * @return array{messages: Collection<int, Message>, has_older: bool, has_newer: bool}
     */
    private function pageAfter($query, int $afterId): array
    {
        $messages = $query
            ->where('id', '>', $afterId)
            ->orderBy('id')
            ->limit(self::PAGE_SIZE + 1)
            ->get();

        $hasNewer = $messages->count() > self::PAGE_SIZE;
        $messages = $messages->take(self::PAGE_SIZE)->values();

        return [
            'messages' => $messages,
            'has_older' => true,
            'has_newer' => $hasNewer,
        ];
    }
}
