<?php

namespace App\Actions\Message;

use App\Models\Message;
use App\Models\MessageRead;
use Illuminate\Support\Facades\Auth;

class MarkAsReadMessageAction
{
    public function execute(Message $message): void
    {
        MessageRead::firstOrCreate([
            'message_id' => $message->id,
            'user_id' => Auth::id(),
            'read_at' => now(),
        ]);
    }
}