<?php

namespace Tests\E2E\Messages;

use App\Models\Chat;
use Database\Seeders\ChatsSeeder;
use Database\Seeders\MessagesSeeder;
use Database\Seeders\UsersSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class MessagesApiTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed([
            UsersSeeder::class,
            ChatsSeeder::class,
            MessagesSeeder::class,
        ]);
    }

    public function test_create_message_success(): void
    {
        $chat = Chat::find(1);
        $requestBody = [
            'text' => 'Test message',
        ];
        $author = $chat->participants()->first();
        $response = $this->postJson(
                                    route('messages.store', [
                                        'user' => $author->id,
                                        'chatId' => $chat->id,
                                    ]),
                                    $requestBody
        );

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'text',
                'chat_id',
                'created_at',
                'updated_at',
            ],
        ]);
        $response->assertJson(value: [
            'data' => [
                'text' => 'Test message',
                'chat_id' => $chat->id,
                'author_id' => $author->id,
            ],
        ]);
    }
}
