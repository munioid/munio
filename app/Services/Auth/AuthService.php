<?php

namespace App\Services\Auth;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function login(array $credentials): array
    {
        $user = User::query()
            ->where('email', $credentials['email'])
            ->first();

        if ($user === null || !Hash::check($credentials['password'], $user->password)) {
            throw new Exception(
                'Maaf, kredensial yang anda masukkan tidak valid.',
                401
            );
        }

        $token = $user->createToken('auth_token')->accessToken;

        return [
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ];
    }

    public function logout(User $user): void
    {
        $user->tokens->each(function ($token) {
            $token->revoke();
        });
    }
}
