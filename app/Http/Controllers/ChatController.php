<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreConversationRequest;
use App\Models\Conversation;
use App\Models\ConversationUser;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ChatController extends Controller
{
    public function index()
    {
        $conversations = Conversation::query()
            ->whereHas('users', function ($query) {
                $query->where('users.id', Auth::id());
            })
            ->with([
                'users' => function ($query) {
                    $query
                        ->where('users.id', '!=', auth()->id())
                        ->select(
                            'users.id',
                            'users.name',
                            'users.username',
                            'users.avatar',
                            'users.last_seen_at'
                        );
                }
            ])
            ->get();

        return Inertia::render('Chat', [
            'conversations' => $conversations,
            'conversation' => null,
        ]);
    }

    public function show(string $id)
    {
        
        $conversationUser = ConversationUser::where('conversation_id', $id)->where('user_id', Auth::id())->first();

        if (!$conversationUser) {
            return redirect()->route('chatboard');
        }

        $conversation = Conversation::with(['messages.user', 'users'])->find($id);

        $conversations = Conversation::query()
            ->whereHas('users', function ($query) {
                $query->where('users.id', auth()->id());
            })
            ->with([
                'users' => function ($query) {
                    $query->where('users.id', '!=', auth()->id())
                        ->select(
                            'users.id',
                            'users.name',
                            'users.username',
                            'users.avatar',
                            'users.last_seen_at'
                        );
                }
            ])
            ->get();

        return Inertia::render('Chat', [
            'conversations' => $conversations,
            'conversation' => $conversation,
        ]);
    }

    public function store(StoreConversationRequest $request)
    {
        $user = auth()->user();

        $conversation = Conversation::create([
            'created_by' => $user->id,
            'type' => 'private',
        ]);

        $conversationUser = ConversationUser::create([
            'conversation_id' => $conversation->id,
            'user_id' => $request->validated('other_user_id'),
            'joined_at' => now(),
            'is_muted' =>  false,
            'is_pinned' =>  false,
        ]);


        ConversationUser::create([
            'conversation_id' => $conversation->id,
            'user_id' => $user->id,
            'joined_at' => now(),
            'is_muted' =>  false,
            'is_pinned' =>  false,
        ]);

        Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => $user->id,
            'body' => 'Hi'
        ]);

        return redirect()->route('chat.show', $conversation->id);

    }
}
