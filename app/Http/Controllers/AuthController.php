<?php

namespace App\Http\Controllers;

use App\Actions\Auth\LoginUserAction;
use App\Actions\Auth\RegisterUserAction;
use App\Http\Requests\Auth\LoginUserRequest;
use App\Http\Requests\Auth\RegisterUserRequest;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AuthController extends Controller
{
    public function showRegisterForm()
    {
        return Inertia::render('auth/Register');
    }

    public function register(
        RegisterUserRequest $request,
        RegisterUserAction $action,
    ) {
        $user = $action->execute($request->validated());
        // return redirect()->route('verification.notice');
        return redirect()->route('chatboard');
    }

    public function showLoginForm()
    {
        return Inertia::render('auth/Login');
    }

    public function login(
        LoginUserRequest $request,
        LoginUserAction $action,
    ) {
        if ($action->execute($request->validated())) {
            $request->session()->regenerate();
            return redirect()->route('chatboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials are not match.',
        ])->onlyInput('email');
    }

    public function verifyEmailPage()
    {
        return Inertia::render('auth/VerifyEmail');   
    }

    public function verifyEmail(EmailVerificationRequest $request)
    {
        $request->fulfill();
        return redirect()->route('chatboard');
    }

    public function resendVerificationLink(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();
        return Inertia::flash([
            'id' => microtime(),
            'message' => 'Verification link is sent successfully.',
            'type' => 'success',
        ])->back();
    }

    public function logout(Request $request)
    {
        if ($user = $request->user()) {
            $user->forceFill([
                'remember_token' => null,
            ])->save();
        }

        // Auth::logout();

        // $request->session()->invalidate();
        // $request->session()->regenerateToken();

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('welcome');
    }
}
