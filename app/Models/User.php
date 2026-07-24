<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

#[Fillable(['name', 'username', 'email', 'password', 'avatar', 'last_seen_at'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_seen_at' => 'datetime',
        ];
    }

    /**
     * Conversations created by this user.
     */
    public function conversations(): BelongsToMany
    {
        return $this->belongsToMany(
            Conversation::class,
            'conversation_users',
            'user_id',
            'conversation_id'
        )->withTimestamps();
    }

    /**
     * Pivot records for conversations this user is a member of.
     */
    public function conversationUsers(): HasMany
    {
        return $this->hasMany(ConversationUser::class, 'user_id');
    }

    /**
     * Conversations this user is a member of (many-to-many via conversation_users).
     */
    public function conversationsAsMember(): BelongsToMany
    {
        return $this->belongsToMany(Conversation::class, 'conversation_users', 'user_id', 'conversation_id')
            ->withPivot(['role', 'joined_at', 'last_read_message_id', 'is_muted', 'is_pinned'])
            ->withTimestamps();
    }

    /**
     * Messages sent by this user.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'user_id');
    }

    /**
     * Accessor for avatar
     */
    public function avatar(): Attribute
    {
        return Attribute::make(
            get: fn(?string $value) => $value ? Storage::url($value) : null,
        );
    }

    public function lastSeenAt(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value
                ? Carbon::parse($value)->diffForHumans()
                : null,
        );
    }
}
