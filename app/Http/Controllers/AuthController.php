<?php

namespace App\Http\Controllers;

use App\Exceptions\AppLogicException;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Services\Auth\AuthService;
use App\Services\Auth\DTO\LoginDTO;
use App\Services\Auth\DTO\RegisterDTO;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Knuckles\Scribe\Attributes\BodyParam;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\UrlParam;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    #[Group('Auth')]
    #[BodyParam('email', type: 'email', description: "Must be a email address")]
    #[BodyParam('password', type: 'string', description: "Must be a password")]
    #[BodyParam('password_confirmation', type: 'string', description: "Must be a password confirmation")]
    public function register(RegisterRequest $request)
    {
        $registerDTO = new RegisterDTO($request->validated());

        $authService = app()->make(AuthService::class);
        [$user, $token] = $authService->register($registerDTO);

        return (new JsonResource([
                'user' => $user,
                'token' => $token,
            ]))->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    #[Group('Auth')]
    #[BodyParam('email', type: 'email', description: "Must be a email address")]
    #[BodyParam('password', type: 'string', description: "Must be a password")]
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string',
        ]);

        $loginDTO = new LoginDTO($request->only('email', 'password'));
        $authService = app()->make(AuthService::class);
        $token = $authService->login($loginDTO);

        return (new JsonResource(['token' => $token,]))->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    public function logout()
    {
        $authService = app()->make(AuthService::class);
        $authService->logout();

        return (new JsonResource(['message' => 'Logged out successfully']))->response()
            ->setStatusCode(Response::HTTP_OK);
    }
}
