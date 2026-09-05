<?php

namespace App\Http\Controllers;

use App\Actions\Message\DeleteMessageAction;
use App\Actions\Message\MarkAsReadMessageAction;
use App\Actions\Message\SendMessageAction;
use App\Actions\Message\UpdateMessageAction;
use App\Http\Requests\Message\StoreMessageRequest;
use App\Http\Requests\Message\UpdateMessageRequest;
use App\Http\Resources\MessageResource;
use App\Models\Conversation;
use App\Models\Message;
use App\Queries\Chat\GetConversationMessages;

class MessageController extends Controller
{
    public function index(
        Conversation $conversation,
        GetConversationMessages $query
    ) {
        $this->authorize('view', $conversation);
        $result = $query->execute($conversation);

        return MessageResource::collection($result['messages'])->additional([
            'meta' => [
                'has_older' => $result['has_older'],
                'has_newer' => $result['has_newer'],
                'first_unread_message_id' => $result['first_unread_message_id'],
            ],
        ]);
    }

    public function store(
        StoreMessageRequest $request,
        SendMessageAction $action
    ) {
        $message = $action->execute($request->validated());

        return $message;
    }

    public function markAsRead(
        Message $message,
        MarkAsReadMessageAction $action,
    ) {
        $this->authorize('view', $message->conversation);
        $action->execute($message);

        return response()->json([
            'message_id' => $message->id,
        ]);
    }

    public function update(
        Message $message,
        UpdateMessageRequest $request,
        UpdateMessageAction $action,
    ) {
        $result = $action->execute($message, $request->validated('body'));

        return response()->json([
            'success' => true,
            'status' => 200,
            'message' => $result,
        ]);
    }

    public function destroy(
        Message $message,
        DeleteMessageAction $action
    ) {
        $action->execute($message);

        return response()->json([
            'success' => true,
            'message' => 'Message deleted successfully.',
            'status' => 200,
        ]);
    }
}
