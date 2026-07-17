<?php

namespace App\Events\Message;

use App\Models\Message;
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
    public function __construct(public Message $message) {
        $this->message->load('user');
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return  Channel
     */
    public function broadcastOn(): Channel
    {
        return new PrivateChannel('chats.' . $this->message->conversation_id);
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
            'user_id' => $this->message->user_id,
            'conversation_id' => $this->message->conversation_id,
            'created_at' => $this->message->created_at,

            'user' => [
                'id' => $this->message->user->id,
                'name' => $this->message->user->name,
                'username' => $this->message->user->username,
                'avatar' => $this->message->user->avatar,
            ],
        ];
    }
}
