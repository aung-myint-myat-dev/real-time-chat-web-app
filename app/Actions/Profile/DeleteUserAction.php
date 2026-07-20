<?php

namespace App\Actions\Profile;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DeleteUserAction
{
    public function execute(string $password, int $id)
    {
        $user = User::find($id);

        if($user && Hash::check($password, $user->password)) {
            $user->delete();
            return true;
        }

        return false;
    }
}