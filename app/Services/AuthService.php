<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function registration(array $params): array
    {
        $password = Hash::make($params['password']);
        $user = User::create([
            'login' => $params['login'],
            'first_name' => $params['first_name'],
            'last_name' => $params['last_name'],
            'birthday' => $params['birthday'],
            'password' => $params['password'],
        ]);

        return [
            'user' => $user,
            'access_token' => $user->createToken('api')->plainTextToken
        ];
    }

    public function login(array $params): array
    {
        $user = User::where('login', $params['login'])->first();

        if (!$user || !Hash::check($params['password'], $user->password)) {
            throw new HttpResponseException(response()->json([
                'success'   => false,
                'message'   => 'Incorrect login or password',
            ], 422));
        }

        return [
            'user' => $user,
            'access_token' => $user->createToken('api')->plainTextToken
        ];
    }

    public function logout(User $user): void
    {
        $user->tokens()->delete();
    }
}

