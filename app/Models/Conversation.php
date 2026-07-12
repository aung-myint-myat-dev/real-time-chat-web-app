<?php

namespace App\Models;

use Database\Factories\ConversationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
{
    /** @use HasFactory<ConversationFactory> */
    use HasFactory;

    /**
     * The user who created this conversation.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Pivot records of users in this conversation.
     */
    public function conversationUsers(): HasMany
    {
        return $this->hasMany(ConversationUser::class, 'conversation_id');
    }

    /**
     * Users who are members of this conversation (many-to-many via conversation_users).
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'conversation_users', 'conversation_id', 'user_id')
            ->withPivot(['role', 'joined_at', 'last_read_message_id', 'is_muted', 'is_pinned'])
            ->withTimestamps();
    }

    /**
     * Messages in this conversation.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'conversation_id');
    }
}