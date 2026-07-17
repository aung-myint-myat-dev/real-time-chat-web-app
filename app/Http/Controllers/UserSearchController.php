<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserSearchRequest;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class UserSearchController extends Controller
{
    public function index(UserSearchRequest $request)
    {
        $search = $request->validated('q', '');

        $currentUserId = auth()->id();

        $users = User::query()
            ->where('username', $search)
            ->where('id', '!=', $currentUserId)
            ->get()
            ->map(function ($user) use ($currentUserId) {

                $conversation = Conversation::query()
                    ->where('type', 'private')  
                    ->whereHas('users', function ($q) use ($currentUserId) {
                        $q->where('user_id', $currentUserId);
                    })
                    ->whereHas('users', function ($q) use ($user) {
                        $q->where('user_id', $user->id);
                    })
                    ->first();

                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'username' => $user->username,
                    'avatar' => $user->avatar,
                    'conversation_id' => $conversation?->id,
                ];
            });

        return response()->json($users);
    }
}
