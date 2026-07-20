<?php

namespace App\Actions\Profile;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UploadProfileImageAction
{
    public function execute(UploadedFile $image, User $user): bool
    {
        $rawUserAvatar = $user->getRawOriginal('avatar');

        if($rawUserAvatar && Storage::disk('public')->exists($rawUserAvatar)) {
            Storage::disk('public')->delete($rawUserAvatar);
        }

        $filename = 'user_' . $user->id . '_' . time() . '.' . $image->getClientOriginalExtension();
        $image_path = Storage::disk('public')->putFileAs('avatars', $image, $filename);

        return $user->update([
            'avatar' => $image_path,
        ]);
    }
}