<?php

namespace Tests\E2E\Users;

use App\Models\User;
use Database\Seeders\UsersSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class UsersApiTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed([
            UsersSeeder::class,
        ]);

        $this->actingAs(User::find(1));
    }

    public static function usersListProvider()
    {
        return [
            [20, 1, 20],
            [20, 2, 11],
            [30, 1, 30],
            [30, 2, 1],
        ];
    }

    #[DataProvider('usersListProvider')]
    public function test_list_users_with_paginate_success($perPage, $pageNumber, $countRecords)
    {
        $requestQuery = [
            'perPage' => $perPage,
            'pageNumber' => $pageNumber,
        ];

        $response = $this->getJson(route('users.index') . '?' . http_build_query($requestQuery));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'first_name',
                    'last_name',
                    'email',
                ]
            ],
        ]);
        $response->assertJsonCount($countRecords, 'data');
        $this->assertEquals($response->json('meta.current_page'), $pageNumber);
        $this->assertEquals($response->json('meta.per_page'), $perPage);
    }

//    public function test_get_user_info()
//    {
//        $response = $this->getJson(route('users.get', ['user' => 1]));
//
//        $response
//            ->assertStatus(200)
//            ->assertJson([
//                'success' => true,
//                'message' => 'Пользователь успешно авторизован',
//            ]);
//
//    }
}
