<?php

use App\Http\Controllers\UserSearchController;
use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

require __DIR__ . '/auth.php';

Route::middleware(['guest'])->get('/', function () {
    return Inertia::render('Welcome');
})->name('welcome');

Route::middleware(['auth'])
    ->group(function () {
        $chats = [
            [
                'id' => 1,
                'name' => 'Aung Aung',
                'avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=200&auto=format&fit=crop',
                'last_message' => 'မနက်ဖြန်မှ တွေ့မယ်နော် ဆရာ...',
                'time' => '10:45 AM',
                'unread_count' => 2
            ],
            [
                'id' => 2,
                'name' => 'Su Su',
                'avatar' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?q=80&w=200&auto=format&fit=crop',
                'last_message' => 'ဟုတ်ကဲ့ အစ်ကို၊ ကုဒ်ပို့လိုက်ပြီရှင်။',
                'time' => '09:12 AM',
                'unread_count' => 0
            ],
            [
                'id' => 3,
                'name' => 'Min Min',
                'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=200&auto=format&fit=crop',
                'last_message' => 'Project ပြီးသွားပြီလားဗျာ?',
                'time' => 'Yesterday',
                'unread_count' => 0
            ],
            [
                'id' => 4,
                'name' => 'Thiri',
                'avatar' => 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?q=80&w=200&auto=format&fit=crop',
                'last_message' => 'Inertia reload ဖြစ်တာ အဆင်ပြေသွားပြီလား?',
                'time' => '2 days ago',
                'unread_count' => 5
            ]
        ];
        Route::get('/chat', function () use ($chats) {
            return Inertia::render('Chat', [
                'chats' => $chats,
            ]);
        })->name('chatboard');

        Route::get('/chat/{id}', function ($id) use ($chats) {
            $chat = collect($chats)->firstWhere('id', $id);
            return Inertia::render('Chat', [
                'chats' => $chats,
                'chat' => $chat,
                'activeChatId' => $id,
            ]);
        });

        Route::get('/users/search', [UserSearchController::class, 'index']);

        Route::get('/settings', function () {
            return Inertia::render('Setting');
        });

        Route::get('/profile', function () {
            return Inertia::render('Profile');
        });

        Route::get('/saved/messages', function () {
            return Inertia::render('SavedMessages');
        });
    });
