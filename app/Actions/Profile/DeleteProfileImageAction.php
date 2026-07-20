<?php

namespace App\Actions\Profile;

use App\Models\User;
use Illuminate\Support\Facades\Storage;

class DeleteProfileImageAction
{
    public function execute(User $user): bool
    {
        $rawUserAvatar = $user->getRawOriginal('avatar');

        if ($rawUserAvatar && Storage::disk('public')->exists($rawUserAvatar)) {
            Storage::disk('public')->delete($rawUserAvatar);
        }
        
        return $user->update([
            'avatar' => null,
        ]);
    }
}
