<?php

namespace Tests\Feature;

use App\Models\Conversation;
use App\Models\ConversationUser;
use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class MarkConversationAsReadTest extends TestCase
{
    use RefreshDatabase;

    public function test_viewing_messages_marks_only_unread_up_to_the_visible_message(): void
    {
        [$reader, $sender, $conversation, $messages] = $this->makeConversationWithUnreadMessages();

        $this->actingAs($reader)
            ->postJson(route('chats.read', $conversation), [
                'message_id' => $messages[1]->id,
            ])
            ->assertOk();

        $this->assertDatabaseHas('message_reads', [
            'message_id' => $messages[0]->id,
            'user_id' => $reader->id,
        ]);
        $this->assertDatabaseHas('message_reads', [
            'message_id' => $messages[1]->id,
            'user_id' => $reader->id,
        ]);
        $this->assertDatabaseMissing('message_reads', [
            'message_id' => $messages[2]->id,
            'user_id' => $reader->id,
        ]);

        $this->assertSame(
            $messages[1]->id,
            $conversation->conversationUsers()->where('user_id', $reader->id)->value('last_read_message_id'),
        );
    }

    public function test_opening_a_conversation_does_not_mark_messages_as_read(): void
    {
        [$reader, $sender, $conversation, $messages] = $this->makeConversationWithUnreadMessages();

        $this->actingAs($reader)
            ->get(route('chats.show', $conversation))
            ->assertOk();

        $this->assertDatabaseMissing('message_reads', [
            'message_id' => $messages[0]->id,
            'user_id' => $reader->id,
        ]);
        $this->assertNull(
            $conversation->conversationUsers()->where('user_id', $reader->id)->value('last_read_message_id'),
        );
    }

    public function test_own_messages_are_not_marked_as_read(): void
    {
        [$reader, $sender, $conversation] = $this->makeConversationWithUnreadMessages();

        $ownMessage = Message::factory()
            ->forConversation($conversation)
            ->sentBy($reader)
            ->create(['body' => 'My reply']);

        $this->actingAs($reader)
            ->postJson(route('chats.read', $conversation), [
                'message_id' => $ownMessage->id,
            ])
            ->assertOk();

        $this->assertDatabaseMissing('message_reads', [
            'message_id' => $ownMessage->id,
            'user_id' => $reader->id,
        ]);
    }

    public function test_single_message_mark_as_read_also_marks_earlier_unread_messages(): void
    {
        [$reader, $sender, $conversation, $messages] = $this->makeConversationWithUnreadMessages();

        $this->actingAs($reader)
            ->postJson(route('message.read', $messages[1]))
            ->assertOk();

        $this->assertDatabaseHas('message_reads', [
            'message_id' => $messages[0]->id,
            'user_id' => $reader->id,
        ]);
        $this->assertDatabaseHas('message_reads', [
            'message_id' => $messages[1]->id,
            'user_id' => $reader->id,
        ]);
        $this->assertDatabaseMissing('message_reads', [
            'message_id' => $messages[2]->id,
            'user_id' => $reader->id,
        ]);
    }

    public function test_messages_from_unread_start_at_the_first_unread_message(): void
    {
        [$reader, $sender, $conversation, $messages] = $this->makeConversationWithUnreadMessages();

        $readMessage = $messages[0];
        $conversation->conversationUsers()
            ->where('user_id', $reader->id)
            ->update(['last_read_message_id' => $readMessage->id]);

        $response = $this->actingAs($reader)
            ->getJson(route('message.index', $conversation).'?from_unread=1')
            ->assertOk();

        $ids = collect($response->json('data'))->pluck('id')->all();

        $this->assertSame($messages[1]->id, $ids[0]);
        $this->assertNotContains($readMessage->id, $ids);
        $this->assertSame($messages[1]->id, $response->json('meta.first_unread_message_id'));
        $this->assertTrue($response->json('meta.has_older'));
        $this->assertFalse($response->json('meta.has_newer'));
    }

    /**
     * @return array{0: User, 1: User, 2: Conversation, 3: Collection<int, Message>}
     */
    private function makeConversationWithUnreadMessages(): array
    {
        $reader = User::factory()->create();
        $sender = User::factory()->create();
        $conversation = Conversation::factory()->createdBy($reader)->private()->create();

        ConversationUser::factory()->forConversation($conversation)->forUser($reader)->create();
        ConversationUser::factory()->forConversation($conversation)->forUser($sender)->create();

        $messages = collect([
            Message::factory()->forConversation($conversation)->sentBy($sender)->create(['body' => 'First']),
            Message::factory()->forConversation($conversation)->sentBy($sender)->create(['body' => 'Second']),
            Message::factory()->forConversation($conversation)->sentBy($sender)->create(['body' => 'Third']),
        ]);

        return [$reader, $sender, $conversation, $messages];
    }
}
