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
    public function testGetChatByUser(): void
    {
        $response = $this->getJson(route('chats.index', ['user' => 1]));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'slug',
                    'options',
                    'chat_users',
                ]
            ]
        ]);
        $response->assertJsonCount(1);
    }

    public function testCreateChatSuccess()
    {
        $userId = 1;
        $requestBody = [
            'slug' => 'test_chat',
            'userIds' => [1, 3],
            'options' => [],
        ];
        $response = $this->postJson(route('chats.store', ['user' => $userId]), $requestBody);

        $response->assertCreated();
        $response->assertJsonStructure([
            'data' => [
                'id',
            ]
        ]);
    }

    public function testDeleteChatSuccess()
    {
        $userId = 2;
        $response = $this->postJson(route('chats.store', ['user' => $userId]), [
            'slug' => 'test_chat',
            'userIds' => [$userId, 3],
            'options' => [],
        ]);
        $chatId = $response->json('data.id');

        $this->deleteJson(route('chats.delete', ['user' => $userId, 'chat' => $chatId]))
            ->assertNoContent();
        $this->assertDatabaseMissing('chats', ['id' => $chatId]);
    }



}
