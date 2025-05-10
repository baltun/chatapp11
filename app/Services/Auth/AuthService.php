<?php

namespace App\Services\Auth;

use App\Exceptions\AppLogicException;
use App\Models\User;
use App\Services\Auth\DTO\LoginDTO;
use App\Services\Auth\DTO\RegisterDTO;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthService
{
    public function register(RegisterDTO $registerDTO)
    {
        $user = User::where('email', $registerDTO->email)->first();

        if ($user) {
            throw new AppLogicException('User with this email already exists', Response::HTTP_BAD_REQUEST);
        }
        $user = User::create([
            'first_name' => $registerDTO->first_name,
            'last_name' => $registerDTO->last_name,
            'email' => $registerDTO->email,
            'password' => bcrypt($registerDTO->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            $user,
            $token,
        ];
    }

    public function login(LoginDTO $loginDTO)
    {
        $user = User::where('email', $loginDTO->email)->first();
        if (!$user || !password_verify($loginDTO->password, $user->password)) {
            throw new AppLogicException('Invalid credentials');
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return $token;
    }

    public function logout(): void
    {
        Auth::logout();
    }
}
