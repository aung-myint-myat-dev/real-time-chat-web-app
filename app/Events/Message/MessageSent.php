<?php

namespace App\Events\Message;

use App\Http\Resources\MessageResource;
use App\Models\Message;
use ArrayAccess;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public Message $message)
    {
        $this->message->load(['user', 'conversation']);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return  array
     */
    public function broadcastOn(): array
    {
        $otherUserId = $this->message->conversation->users
            ->firstWhere('id', '!=', auth()->id())
            ->id;

        return [
            new PrivateChannel('chats.' . $this->message->conversation_id),
            new PrivateChannel('users.' . $otherUserId)
        ];
    }

    public function broadcastAs()
    {
        return 'message.sent';
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->message->id,
            'body' => $this->message->body,
            'conversation_id' => $this->message->conversation_id,

            'user' => [
                'id' => $this->message->user->id,
                'name' => $this->message->user->name,
            ],

            'created_at' => $this->message->created_at,
        ];
    }
}
