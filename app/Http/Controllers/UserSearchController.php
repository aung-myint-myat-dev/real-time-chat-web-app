<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserSearchRequest;
use App\Queries\User\SearchUsers;


class UserSearchController extends Controller
{
    public function index(UserSearchRequest $request, SearchUsers $query)
    {
        $users = $query->execute($request->validated('q', ''), auth()->id());

        return response()->json($users);
    }
}
