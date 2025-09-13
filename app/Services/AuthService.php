<?php

namespace App\Services;

use App\Mail\ResetPasswordMail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function login(array $credentials): array
    {
        if (!Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        /** @var User $user */
        $user = Auth::user();


        if ($user->status != 1) {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => ['User is inactive. Please contact administrator.'],
            ]);
        }

        $token = $user->createToken('AuthToken')->accessToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }



    public function logout(): void
    {
        $user = Auth::user();

        if ($user) {
            $user->token()->revoke();
        }
    }

    public function sendForgotPasswordLink(string $email)
    {
        $user = User::where('email', $email)->first();

        if (!$user) {
            return [
                'success' => false,
                'message' => 'Email not found'
            ];
        }

        $token = Password::createToken($user);

        $resetUrl = config('app.url') 
                    . '/reset-password?token=' . $token 
                    . '&email=' . urlencode($user->email);
        
        Mail::to($user->email)->send(new ResetPasswordMail($resetUrl));

        return [
            'success' => true,
            'message' => 'Password reset link sent'
        ];
    }

    public function resetPassword(array $data)
    {
        $status = Password::reset(
            $data,
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return [
                'success' => true,
                'message' => 'Password reset successfully'
            ];
        }

        return [
            'success' => false,
            'message' => __($status)
        ];
    }
}
