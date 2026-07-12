<?php

namespace Database\Factories;

use App\Models\ConversationUser;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ConversationUser>
 */
class ConversationUserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'conversation_id' => Conversation::factory(),
            'user_id' => User::factory(),
            'role' => 'member',
            'joined_at' => now(),
        ];
    }

    /**
     * Set the conversation for this pivot record.
     */
    public function forConversation(Conversation $conversation): static
    {
        return $this->state(fn (array $attributes) => [
            'conversation_id' => $conversation->id,
        ]);
    }

    /**
     * Set the user for this pivot record.
     */
    public function forUser(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $user->id,
        ]);
    }

    /**
     * Indicate that the user is an admin of the conversation.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
        ]);
    }

    /**
     * Indicate that the user is the owner of the conversation.
     */
    public function owner(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'owner',
        ]);
    }

    /**
     * Indicate that the conversation is muted for this user.
     */
    public function muted(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_muted' => true,
        ]);
    }

    /**
     * Indicate that the conversation is pinned for this user.
     */
    public function pinned(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_pinned' => true,
        ]);
    }
}