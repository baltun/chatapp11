<?php

namespace Tests\E2E\Messages;

use App\Models\Chat;
use Database\Seeders\ChatsSeeder;
use Database\Seeders\MessagesSeeder;
use Database\Seeders\UsersSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
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

        $this->actingAs(
            auth()->loginUsingId(1)
        );
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
                                        'chat' => $chat->id,
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

    public function test_create_message_unauthorized(): void
    {
        Auth::logout();
        $chat = Chat::find(1);
        $requestBody = [
            'text' => 'Test message',
        ];
        $response = $this->postJson(
                                    route('messages.store', [
                                        'user' => 1,
                                        'chat' => $chat->id,
                                    ]),
                                    $requestBody
        );

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_list_messages()
    {
        $requestBody = [
            'pageNumber' => 1,
            'perPage' => 20,
            'searchText' => '',
        ];
        $response = $this->getJson(
                                    route('messages.index', [
                                        'user' => 1,
                                        'chat' => 1,
                                    ]) . '?' . http_build_query($requestBody)
        );
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'text',
                    'author_id',
                    'created_at',
                ],
            ],
        ]);
        $response->assertJsonCount(20, 'data');

    }

}
