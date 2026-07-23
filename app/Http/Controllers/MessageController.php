<?php

namespace App\Http\Controllers;

use App\Actions\Message\SendMessageAction;
use App\Http\Requests\Message\StoreMessageRequest;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function store(
        StoreMessageRequest $request,
        SendMessageAction $action
    ) {
        $action->execute($request->validated());

        // return redirect()->route('chat.show', $request->validated('conversation_id'));
        return redirect()->back(); 
    }
}
