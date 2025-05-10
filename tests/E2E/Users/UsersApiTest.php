<?php

namespace Tests\E2E\Users;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UsersApiTest extends TestCase
{
    use RefreshDatabase;

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
