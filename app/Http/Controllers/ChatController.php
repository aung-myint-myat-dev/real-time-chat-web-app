<?php

namespace App\Http\Controllers;

use App\Actions\Chat\MarkConversationAsReadAction;
use App\Actions\Chat\StartConversationAction;
use App\Http\Requests\StoreConversationRequest;
use App\Models\Conversation;
use App\Queries\Chat\GetUserConversations;
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
        MarkConversationAsReadAction $markConversationAsRead,
    ) {
        $this->authorize('view', $conversation);

        $markConversationAsRead->execute($conversation, auth()->user());

        return Inertia::render('Chat', [
            'conversations' => $query->execute(auth()->user()),
            'conversation' => $conversation->load([
                'messages.user',
                'users',
            ]),
        ]);
    }

    public function store(
        StoreConversationRequest $request,
        StartConversationAction $action,
    ) {
        $conversation = $action->execute($request->validated());

        return redirect()->route('chat.show', $conversation->id);
    }

    public function markAsRead(
        Conversation $conversation,
        MarkConversationAsReadAction $markConversationAsRead,
    ) {
        $this->authorize('view', $conversation);

        $markConversationAsRead->execute(
            $conversation,
            auth()->user(),
            request()->integer('message_id') ?: null,
        );

        return redirect()->back();
    }
}
