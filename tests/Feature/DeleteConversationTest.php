<?php

namespace Tests\Feature;

use App\Models\Conversation;
use App\Models\ConversationUser;
use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class DeleteConversationTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_participant_can_delete_a_conversation_for_everyone(): void
    {
        [$reader, $sender, $conversation, $messages] = $this->makeConversation();

        $this->actingAs($reader)
            ->deleteJson(route('chats.destroy', $conversation))
            ->assertOk();

        $this->assertDatabaseMissing('conversations', [
            'id' => $conversation->id,
        ]);

        foreach ($messages as $message) {
            $this->assertDatabaseMissing('messages', [
                'id' => $message->id,
            ]);
        }

        $this->assertDatabaseMissing('conversation_users', [
            'conversation_id' => $conversation->id,
        ]);
    }

    public function test_a_stranger_cannot_delete_a_conversation(): void
    {
        [$reader, $sender, $conversation] = $this->makeConversation();
        $stranger = User::factory()->create();

        $this->actingAs($stranger)
            ->deleteJson(route('chats.destroy', $conversation))
            ->assertForbidden();

        $this->assertDatabaseHas('conversations', [
            'id' => $conversation->id,
        ]);
    }

    /**
     * @return array{0: User, 1: User, 2: Conversation, 3: Collection<int, Message>}
     */
    private function makeConversation(): array
    {
        $reader = User::factory()->create();
        $sender = User::factory()->create();
        $conversation = Conversation::factory()->createdBy($reader)->private()->create();

        ConversationUser::factory()->forConversation($conversation)->forUser($reader)->create();
        ConversationUser::factory()->forConversation($conversation)->forUser($sender)->create();

        $messages = collect([
            Message::factory()->forConversation($conversation)->sentBy($sender)->create(['body' => 'Hello']),
            Message::factory()->forConversation($conversation)->sentBy($reader)->create(['body' => 'Hi']),
        ]);

        return [$reader, $sender, $conversation, $messages];
    }
}
