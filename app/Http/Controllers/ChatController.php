<?php

namespace App\Http\Controllers;

use App\Actions\Chat\StartConversationAction;
use App\Http\Requests\StoreConversationRequest;
use App\Models\Conversation;
use App\Queries\Chat\GetUserConversations;
use Illuminate\Http\Request;
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

    public function show(Conversation $conversation, GetUserConversations $query)
    {

        $this->authorize('view', $conversation);

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
        StartConversationAction $action
    ) {

        $conversation = $action->execute($request->validated());

        return redirect()->route('chat.show', $conversation->id);
    }
}
