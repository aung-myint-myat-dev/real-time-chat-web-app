<?php

namespace App\Http\Controllers;

use App\Events\Message\MessageSent;
use App\Http\Requests\Message\StoreMessageRequest;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function store(StoreMessageRequest $request)
    {

        $validated = $request->validated();

        $message = Message::create([
            'conversation_id' => $validated['conversation_id'],
            'user_id' => $validated['user_id'],
            'body' => $validated['body'],
            'reply_message_id' => $validated['reply_message_id'],
            'type' => $validated['type']
        ]);

        broadcast(new MessageSent($message))->toOthers();

        return back();
    }
}
