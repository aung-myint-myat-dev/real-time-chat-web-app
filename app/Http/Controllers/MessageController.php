<?php

namespace App\Http\Controllers;

use App\Actions\Message\SendMessageAction;
use App\Http\Requests\Message\StoreMessageRequest;
use App\Models\Conversation;
use Illuminate\Http\Request;

class MessageController extends Controller
{

    public function index(Conversation $conversation)
    {
    
        $this->authorize('view', $conversation);

        return $conversation->messages()
            ->latest()->get();
            // ->paginate(30);

    }

    public function store(
        StoreMessageRequest $request,
        SendMessageAction $action
    ) {
        $message = $action->execute($request->validated());
        return $message; 
    }
}
