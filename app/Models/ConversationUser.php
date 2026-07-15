<?php

namespace App\Models;

use Database\Factories\ConversationUserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConversationUser extends Model
{
    /** @use HasFactory<ConversationUserFactory> */
    use HasFactory;

    protected $fillable = ['conversation_id', 'user_id', 'role', 'joined_at', 'last_read_message_id', 'is_muted', 'is_pinned'];
    /**
     * The conversation this pivot record belongs to.
     */
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class, 'conversation_id');
    }

    /**
     * The user this pivot record belongs to.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * The last message read by this user in the conversation.
     */
    public function lastReadMessage(): BelongsTo
    {
        return $this->belongsTo(Message::class, 'last_read_message_id');
    }
}
