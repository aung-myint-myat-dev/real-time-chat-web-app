<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserSearchRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserSearchController extends Controller
{
    public function index(UserSearchRequest $request)
    {
        $search = $request->validated('q', '');

        $users = User::query()
            ->select(['id', 'name', 'username', 'avatar'])
            ->where('id', '!=', auth()->id())
            ->where('username', 'like', "%{$search}%")
            ->limit(20)
            ->get();

        return Inertia::render('Chat', [
            'searchUsers' => $users,
            // 'filters' => [
            //     'search' => $search 
            // ]
        ]);

    }
}
