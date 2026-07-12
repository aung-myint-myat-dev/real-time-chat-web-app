<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create 2 users with known credentials for testing
        $user1 = User::factory()->create([
            'name' => 'mad',
            'username' => 'mad',
            'email' => 'mad@gmail.com',
            'password' => Hash::make('password'),
        ]);

        $user2 = User::factory()->create([
            'name' => 'amm',
            'username' => 'amm',
            'email' => 'amm@gmail.com',
            'password' => Hash::make('password'),
        ]);

        // Create a private conversation between the two users
        $conversation = Conversation::factory()
            ->private()
            ->createdBy($user1)
            ->create();

        // Add both users to the conversation as members
        ConversationUser::factory()
            ->forConversation($conversation)
            ->forUser($user1)
            ->owner()
            ->create();

        ConversationUser::factory()
            ->forConversation($conversation)
            ->forUser($user2)
            ->create();

        // Create messages between the two users
        $messages = [
            ['body' => 'Hey Bob! How are you?', 'sender' => $user1],
            ['body' => 'Hi Alice! I\'m doing great, thanks!', 'sender' => $user2],
            ['body' => 'Did you finish the project report?', 'sender' => $user1],
            ['body' => 'Yes, I just submitted it this morning.', 'sender' => $user2],
            ['body' => 'Awesome! Let\'s discuss the next steps over coffee.', 'sender' => $user1],
            ['body' => 'Sounds like a plan! See you at 3 PM?', 'sender' => $user2],
            ['body' => 'Perfect, see you then!', 'sender' => $user1],
        ];

        foreach ($messages as $messageData) {
            Message::factory()
                ->forConversation($conversation)
                ->sentBy($messageData['sender'])
                ->create([
                    'body' => $messageData['body'],
                ]);
        }
    }
}