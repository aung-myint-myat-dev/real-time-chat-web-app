<?php

namespace App\Actions\Profile;

use App\Models\User;

class UpdateUserAction
{
    public function execute(array $data, int $id)
    {
        $user = User::find($id);

        $user->update([
            'name' => $data['name'] ?? $user->name,
            'username' => $data['username'] ?? $user->username,
        ]);
    }
}