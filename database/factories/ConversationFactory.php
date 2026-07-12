<?php

namespace Database\Factories;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Conversation>
 */
class ConversationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->sentence(1),
            'type' => 'private',
            'created_by' => User::factory(),
        ];
    }

    /**
     * Indicate that the conversation is a group chat.
     */
    public function group(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => fake()->words(3, true),
            'type' => 'group',
        ]);
    }

    /**
     * Indicate that the conversation is a private chat.
     */
    public function private(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => null,
            'type' => 'private',
        ]);
    }

    /**
     * Set the user who created this conversation.
     */
    public function createdBy(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'created_by' => $user->id,
        ]);
    }
}