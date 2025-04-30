<?php

namespace Tests\Feature\Chats;

use App\Services\Chats\DTO\ChatCreateDTO;
use App\Models\Chat;
use App\Models\User;
use App\Services\Chats\ChatsService;
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
     *  + - изменить логику - много участников
     *  + - убрать репозиторий
     *  - написать тесты на API-эндпоинты - статус, структура ответа
     *      + - список
     *      + - добавление
     *      - удаление
     *  + - проверка на уже существующий чат
     *  - тесты сервисов
     *      + - создание
     *      + - список
     *      + - удаление
     *      - редактирование чата
     *  + - добавить API Resources
     *  - обработка исключений
     *  - scribe атрибуты на каждый метод контроллеров
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

        $this->assertContainsOnlyInstancesOf(Chat::class, $chats);
        foreach ($chats as $chat) {
            foreach ($chat->users as $user) {

            }
        }
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
        $chatCreateDto = new ChatCreateDTO([
            'slug' => 'chat2',
            'userIds' => $userIds,
            'options' => [],
        ]);

        $chat = $chatsService->createOrGet($chatCreateDto);

        $this->assertEquals($chat->id, $expectedId);
    }

    /*
     * тест на удаление чата
     */
    public function test_delete_chat(): void
    {
        $user = User::find(1);

        $chatsService = resolve(ChatsService::class);
//        $chat = Chat::find(1);
        $chatId = 1;
        $this->assertCount(1, $user->fresh()->chats);
        $chatsService->delete($chatId);
        $this->assertCount(0, $user->fresh()->chats);
    }
}
