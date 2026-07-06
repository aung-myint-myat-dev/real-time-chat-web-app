<?php

namespace App\Actions\Auth;

use Illuminate\Support\Facades\Auth;

class LoginUserAction
{
    public function execute(array $data): bool
    {
        $credentials = [
            'email' => $data['email'],
            'password' => $data['password'],
        ];
        return Auth::attempt($credentials, $data['remember_me'] ?? false);
    }
}
