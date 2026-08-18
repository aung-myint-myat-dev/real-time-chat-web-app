<?php

namespace App\Queries\Chat;

use App\Models\Conversation;
use Illuminate\Support\Facades\Auth;

class GetConversationMessages
{
    public function execute(Conversation $conversation)
    {
        $page = request()->integer('page', 1);

        $paginator = $conversation->messages()
            ->select([
                'id',
                'conversation_id',
                'user_id',
                'body',
                'created_at',
                'edited_at',
            ])
            ->with([
                'user:id,name,avatar',
                'reads' => function ($query) {
                    $query->where('user_id', Auth::id());
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

        return $paginator;
    }
}
