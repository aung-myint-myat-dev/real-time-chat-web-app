<?php

namespace App\Http\Controllers;

use App\Actions\Chat\DeleteConversationAction;
use App\Actions\Chat\MarkConversationAsReadAction;
use App\Actions\Chat\StartConversationAction;
use App\Http\Requests\StoreConversationRequest;
use App\Models\Conversation;
use App\Queries\Chat\GetUserConversations;
use App\Support\ConversationSidebarFormatter;
use Inertia\Inertia;

class ChatController extends Controller
{
    public function index(GetUserConversations $query)
    {
        return Inertia::render('Chat', [
            'conversations' => $query->execute(auth()->user()),
            'conversation' => null,
        ]);
    }

    public function show(
        Conversation $conversation,
        GetUserConversations $query,
    ) {
        $this->authorize('view', $conversation);

        return Inertia::render('Chat', [
            'conversations' => $query->execute(auth()->user()),
            'conversation' => $conversation->load('users'),
        ]);
    }

    public function store(
        StoreConversationRequest $request,
        StartConversationAction $action,
    ) {
        $conversation = $action->execute($request->validated());

        return redirect()->route('chats.show', $conversation->id);
    }

    public function markAsRead(
        Conversation $conversation,
        MarkConversationAsReadAction $markConversationAsRead,
        ConversationSidebarFormatter $formatter,
    ) {
        $this->authorize('view', $conversation);

        $user = auth()->user();
        $markConversationAsRead->execute(
            $conversation,
            $user,
            request()->integer('message_id') ?: null,
        );

        return response()->json([
            'ok' => true,
            'unread_count' => $formatter->unreadCount($conversation, $user),
            'last_read_message_id' => $conversation->conversationUsers()
                ->where('user_id', $user->id)
                ->value('last_read_message_id'),
        ]);
    }

    public function destroy(
        Conversation $conversation,
        DeleteConversationAction $action,
    ) {
        $this->authorize('delete', $conversation);

        $action->execute($conversation);

        return response()->json([
            'ok' => true,
        ]);
    }
}
