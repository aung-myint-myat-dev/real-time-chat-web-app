<?php

namespace App\Http\Controllers;

use App\Actions\Profile\DeleteProfileImageAction;
use App\Actions\Profile\DeleteUserAction;
use App\Actions\Profile\UpdateUserAction;
use App\Actions\Profile\UpdateUserNameAction;
use App\Actions\Profile\UploadProfileImageAction;
use App\Http\Requests\Profile\DeleteUserRequest;
use App\Http\Requests\Profile\UpdateUserNameRequest;
use App\Http\Requests\Profile\UpdateUserRequest;
use App\Http\Requests\Profile\UploadProfileImageRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ProfileController extends Controller
{
    /**
     * Getting auth user info
     */
    public function index()
    {
        $user = Auth::user();
        return Inertia::render('profile/Index', [
            'user' => $user,
        ]);
    }

    /**
     * Getting other user info
     */
    public function show(int $id)
    {
        $user = User::findOrFail($id);
        return Inertia::render('profile/Show', [
            'user' => $user
        ]);
    }

    /**
     * Updating user info [ name, username ]
     */
    public function update(
        UpdateUserRequest $request,
        UpdateUserAction $action,
    ) {
        $action->execute($request->validated(), Auth::id());
        return Inertia::flash([
            'id' => microtime(),
            'message' => 'User info updated successfully.',
            'type' => 'success',
        ])->back();
    }

    /**
     * Deleting user's account permentatly.
     */
    public function delete(
        DeleteUserRequest $request,
        DeleteUserAction $action
    ) {
        if ($action->execute($request->validated('password'), Auth::id())) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('welcome');
        }

        return back()->withErrors([
            'password' => 'Incorrect password',
        ])->onlyInput('password');
    }

    /**
     * Uploading User Avatar
     */
    public function uploadProfileImage(
        UploadProfileImageRequest $request,
        UploadProfileImageAction $action
    ) {
        if($action->execute($request->validated('avatar'), $request->user())) {
            return Inertia::flash([
                'id' => microtime(),
                'message' => 'Avatar uploaded successfully.',
                'type' => 'success',
            ])->back();
        }

        return back()->withErrors([
            'id' => microtime(),
            'message' => 'Something went wrong.',
            'type' => 'error',
        ]);
    }

    /**
     * Deleting User Avatar
     */
    public function deleteProfileImage(
        DeleteProfileImageAction $action,
        Request $request
    ) {
        if($action->execute($request->user())) {
            return Inertia::flash([
                'id' => microtime(),
                'message' => 'Avatar deleted successfully.',
                'type' => 'success',
            ])->back();
        }
        
        return back()->withErrors([
            'id' => microtime(),
            'message' => 'Something went wrong.',
            'type' => 'error',
        ]);
    }
}
