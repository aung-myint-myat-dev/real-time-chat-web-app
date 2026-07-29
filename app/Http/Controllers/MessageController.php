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
        $page = request()->integer('page', 1);

        $paginator = $conversation->messages()
            ->with('user')
            ->latest() // ORDER BY created_at DESC (or id DESC)
            ->paginate(
                perPage: 30,
                page: $page
            );

        // Reverse only the items so they display oldest → newest
        $paginator->setCollection(
            $paginator->getCollection()->reverse()->values()
        );

        // dd($paginator);
        return $paginator;
    }

    public function store(
        StoreMessageRequest $request,
        SendMessageAction $action
    ) {
        $message = $action->execute($request->validated());
        return $message;
    }
}
