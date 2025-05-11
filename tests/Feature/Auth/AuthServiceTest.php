<?php

namespace Tests\Feature\Auth;

use App\Exceptions\AppLogicException;
use App\Models\User;
use App\Services\Auth\AuthService;
use App\Services\Auth\DTO\LoginDTO;
use App\Services\Auth\DTO\RegisterDTO;
use Database\Seeders\UsersSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

/**
 * todo:
 *  + - список сообщений сортировать по убыванию времени создания
 *      + - возвращать поля: messageId, timestamp, text, кто отправитель
 *      + - возвращать постранично по 20 сообщений
 *  + - переделать вызовы сервисов на DTO
 */
class AuthServiceTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed([
            UsersSeeder::class,
        ]);

    }

    public function test_register_success(): void
    {
        $registerDTO = new RegisterDTO([
            'first_name' => 'admin',
            'last_name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => 'admin',
        ]);
        $authService = $this->app->make(AuthService::class);
        [$user, $token] = $authService->register($registerDTO);

        $this->assertNotNull($user);
        $this->assertNotNull($token);
    }

    public function test_register_fail_exists()
    {
        $registerDTO = new RegisterDTO([
            'first_name' => 'admin',
            'last_name' => 'admin',
            'email' => 'admin@example',
            'password' => 'admin',
        ]);

        $this->expectException(AppLogicException::class);
        $authService = $this->app->make(AuthService::class);
        $authService->register($registerDTO);
    }

    public function test_login_success()
    {
        $loginDTO = new LoginDTO([
            'email' => 'admin@example',
            'password' => 'admin',
        ]);
        $this->expectException(AppLogicException::class);
        $authService = $this->app->make(AuthService::class);
        $token = $authService->login($loginDTO);

        $this->assertNotNull($token);
    }

    public function test_login_fail_email()
    {
        $loginDTO = new LoginDTO([
            'email' => 'wrong@email.domain',
            'password' => 'admin',
        ]);
        $this->expectException(AppLogicException::class);
        $authService = $this->app->make(AuthService::class);

        $this->expectExceptionMessage('Invalid credentials');
        $this->expectException(AppLogicException::class);

        $token = $authService->login($loginDTO);
    }

    public function test_logout()
    {
        $this->ActingAs(User::find(1));
        $this->assertNotNull(Auth::user());

        $authService = $this->app->make(AuthService::class);
        $authService->logout();

        $this->assertNull(Auth::user());
    }
}
