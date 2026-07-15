<?php

namespace Database\Factories;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Message>
 */
class MessageFactory extends Factory
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
            'body' => fake()->paragraph(),
            'type' => 'text',
        ];
    }

    /**
     * Set the conversation for this message.
     */
    public function forConversation(Conversation $conversation): static
    {
        return $this->state(fn (array $attributes) => [
            'conversation_id' => $conversation->id,
        ]);
    }

    /**
     * Set the user (sender) for this message.
     */
    public function sentBy(Usere $user): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $user->id,
        ]);
    }

    /**
     * Indicate that this message is a reply to another message.
     */
    public function replyTo(Message $message): static
    {
        return $this->state(fn (array $attributes) => [
            'reply_message_id' => $message->id,
        ]);
    }

    /**
     * Indicate that this message has been edited.
     */
    public function edited(): static
    {
        return $this->state(fn (array $attributes) => [
            'edited_at' => now(),
        ]);
    }

    /**
     * Indicate that this message has been soft-deleted.
     */
    public function deleted(): static
    {
        return $this->state(fn (array $attributes) => [
            'deleted_at' => now(),
        ]);
    }

    /**
     * Indicate that the message is a system message.
     */
    public function system(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'system',
            'body' => fake()->randomElement([
                'User joined the group',
                'User left the group',
                'Group name was changed',
                'Group image was updated',
            ]),
        ]);
    }

    /**
     * Indicate that the message is an image message.
     */
    public function image(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'image',
            'body' => fake()->imageUrl(),
        ]);
    }

    /**
     * Indicate that the message is a file attachment.
     */
    public function file(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'file',
            'body' => fake()->url(),
        ]);
    }
}