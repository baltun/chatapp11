<?php

namespace E2E\Chats;

use Database\Seeders\ChatsSeeder;
use Database\Seeders\UsersSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChatsApiTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed([
            UsersSeeder::class,
            ChatsSeeder::class,
        ]);
    }

    /**
     * A basic feature test example.
     */
    public function test_get_chats(): void
    {
        $response = $this->getJson(route('chats.index', ['user' => 1]));

        $response->assertStatus(200);
//        dd($response->content());
        $response->assertJsonStructure([
            '*' => [
                'id',
                'slug',
                'chat_users'
            ]
        ]);
        $response->assertJsonCount(1);
    }
}
