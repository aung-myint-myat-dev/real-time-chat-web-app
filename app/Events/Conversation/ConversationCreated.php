<?php

namespace App\Events\Conversation;

use App\Models\Conversation;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ConversationCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public Conversation $conversation) {}

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array
     */
    public function broadcastOn(): array
    {
        return $this->conversation
            ->users
            ->map(fn($user) => new PrivateChannel("users.{$user->id}"))
            ->all();
    }

    public function broadcastAs(): string
    {
        return 'conversation.created';
    }

    public function broadcastWith(): array
    {

        $users = $this->conversation
            ->users()
            ->select(
                'users.id',
                'users.name',
                'users.username',
                'users.avatar',
                'users.last_seen_at'
            )
            ->get();

        return [
            'conversation' => [
                'id' => $this->conversation->id,
                'type' => $this->conversation->type,
                'users' => $users,
                'last_message' => null,
                'unread_count' => 0,
            ]
        ];
    }
}
