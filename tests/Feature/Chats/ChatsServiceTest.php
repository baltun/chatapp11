<?php

namespace Tests\Feature\Chats;

use App\DTO\ChatDTO;
use App\Models\Chat;
use App\Models\User;
use App\Services\ChatsService;
use Database\Seeders\ChatsSeeder;
use Database\Seeders\UsersSeeder;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ChatsServiceTest extends TestCase
{
    use RefreshDatabase;

    /* todo:
     *  - редактирование чата
     *  + - изменить логику - много участников
     *  - убрать репозиторий
     *  - добавить API Resources
     *  - написать тесты на API-эндпоинты - статус, структура ответа
     *  - обработка исключений
     *  - проверка на уже существующий чат
    */
    public function SetUp(): void
    {
        parent::setUp();

        $this->seed([
            UsersSeeder::class,
            ChatsSeeder::class,
        ]);
    }

    public function test_get_all_chats(): void
    {
        $user = User::find(1);

        $chatsService = resolve(ChatsService::class);
        $chats = $chatsService->listForUser($user);

        $this->assertInstanceOf(Chat::class, $chats[0]);
    }

    public static function chatCreateProvider()
    {
        return [
            [[1, 2], 1],
            [[1, 3], 2],
            [[2, 3], 2],
            [[1, 2, 3], 2],
        ];
    }

    #[DataProvider('chatCreateProvider')]
    public function test_create_chat(array $userIds, int $expectedId): void
    {
        $chatsService = resolve(ChatsService::class);
        $chatCreateDto = new ChatDTO([
            'slug' => 'chat2',
            'userIds' => $userIds,
            'options' => [],
        ]);

        $chatId = $chatsService->createOrGet($chatCreateDto);

        $this->assertEquals($chatId, $expectedId);
    }

    public function test_delete_chat(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
