<?php

namespace Tests\E2E\Auth;

use Database\Seeders\ChatsSeeder;
use Database\Seeders\UsersSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed([
            UsersSeeder::class,
        ]);
    }

    public function test_register_fail_existed()
    {
        $response = $this->postJson(route('auth.register'), [
            'first_name' => 'Firstname',
            'last_name' => 'Lastname',
            'email' => 'admin@example',
            'password' => 'admin',
            'password_confirmation' => 'admin',
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    public function test_register_success()
    {
        $response = $this->postJson(route('auth.register'), [
            'first_name' => 'Firstname',
            'last_name' => 'Lastname',
            'email' => 'test@test.ru',
            'password' => 'test',
            'password_confirmation' => 'test',
        ]);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => [
                'user' => [
                    'id',
                    'first_name',
                    'last_name',
                    'email',
                ],
                'token'
            ]
        ]);
    }
    public function test_login_success(): void
    {
        $response = $this->postJson(route('auth.login'), [
            'email' => 'admin@example',
            'password' => 'password',
        ]);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => [
                'token',
            ]
        ]);
    }

    public function test_login_fail_credentials(): void
    {
        $response = $this->postJson(route('auth.login'), [
            'email' => 'wrong@email.domain',
            'password' => 'password',
        ]);
        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJson([
            'errors' => [
                [
                    'status' => Response::HTTP_BAD_REQUEST,
                    'code' => Response::HTTP_BAD_REQUEST,
                    'title' => 'Invalid credentials',
                ]
            ],
        ]);
    }
}
