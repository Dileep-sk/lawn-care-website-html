<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
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
}
