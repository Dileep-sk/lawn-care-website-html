<?php

namespace App\Services;

use App\Mail\ResetPasswordMail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

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

        $token = Str::random(64);
        
        $user->reset_password_token = Hash::make($token);
        $user->reset_password_expires_at = now()->addMinutes(60);
        $user->save();

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
        $user = User::where('email', $data['email'])->first();

        if (!$user || !$user->reset_password_token) {
            return [
                'success' => false,
                'message' => 'Invalid or expired token'
            ];
        }
        
        if (!Hash::check($data['token'], $user->reset_password_token)) {
            return [
                'success' => false,
                'message' => 'Invalid token'
            ];
        }
        
        if ($user->reset_password_expires_at->isPast()) {
            return [
                'success' => false,
                'message' => 'Token has expired'
            ];
        }
        
        $user->password = Hash::make($data['password']);
        $user->reset_password_token = null;
        $user->reset_password_expires_at = null;
        $user->save();

        return [
            'success' => true,
            'message' => 'Password reset successfully'
        ];
    }
}
