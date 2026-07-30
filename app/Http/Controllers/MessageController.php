<?php

namespace App\Http\Controllers;

use App\Actions\Message\SendMessageAction;
use App\Http\Requests\Message\StoreMessageRequest;
use App\Http\Resources\MessageResource;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\MessageRead;
use Illuminate\Http\Request;

class MessageController extends Controller
{

    public function index(Conversation $conversation)
    {
        $this->authorize('view', $conversation);

        $page = request()->integer('page', 1);

        $paginator = $conversation->messages()
            ->with([
                'user',
                'reads' => function ($query) {
                    $query->where('user_id', auth()->id());
                },
            ])
            ->latest()
            ->paginate(
                perPage: 30,
                page: $page
            );

        $paginator->setCollection(
            $paginator->getCollection()->reverse()->values()
        );

        return MessageResource::collection($paginator);
    }

    public function store(
        StoreMessageRequest $request,
        SendMessageAction $action
    ) {
        $message = $action->execute($request->validated());
        return $message;
    }

    public function markAsRead(Message $message)
    {

        // $this->authorize('view', $message);

        MessageRead::firstOrCreate(
            [
                'message_id' => $message->id,
                'user_id' => auth()->id(),
            ],
            [
                'read_at' => now(),
            ]
        );

        return response()->json([
            'message_id' => $message->id,
            'read_at' => now(),
        ]);
    }
}
