<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserSearchRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class UserSearchController extends Controller
{
    public function index(UserSearchRequest $request)
    {
        $search = $request->validated('q', '');

        $users = User::query()
            ->select(['id', 'name', 'username', 'avatar'])
            ->where('id', '!=', Auth::id())
            ->where('username', 'like', "{$search}")
            ->limit(20)
            ->get();

        // return Inertia::render('Chat', [
        //     'searchUsers' => $users,
        //     // 'filters' => [
        //     //     'search' => $search 
        //     // ]
        // ]);

        return response()->json($users);
    }
}
