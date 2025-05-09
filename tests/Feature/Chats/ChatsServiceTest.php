<?php

namespace Tests\Feature\Chats;

use App\Services\Chats\DTO\ChatCreateDTO;
use App\Models\Chat;
use App\Models\User;
use App\Services\Chats\ChatsService;
use Database\Seeders\ChatsSeeder;
use Database\Seeders\UsersSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ChatsServiceTest extends TestCase
{
    use RefreshDatabase;

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
            foreach ($chat->participants as $user) {
                $this->assertInstanceOf(User::class, $user);
            }
        }
    }

    public static function chatCreateProvider()
    {
        return [
            [[1, 2], 'chat1'],
            [[1, 3], 'created_chat_name'],
            [[2, 3], 'created_chat_name'],
            [[1, 2, 3], 'created_chat_name'],
        ];
    }

    #[DataProvider('chatCreateProvider')]
    public function test_create_chat(array $userIds, string $expectedSlug): void
    {
        $chatsService = resolve(ChatsService::class);
        $chatCreateDto = new ChatCreateDTO([
            'slug' => 'created_chat_name',
            'userIds' => $userIds,
            'options' => [],
        ]);

        $chat = $chatsService->createOrGet($chatCreateDto);

        $this->assertEquals($chat->slug, $expectedSlug);
    }

    /*
     * тест на удаление чата
     */
    public function test_delete_chat(): void
    {
        $user = User::find(1);

        $chatsService = resolve(ChatsService::class);
        $chat = $user->chats()->first();

        $this->assertNotNull($chat);
        $this->assertCount(1, $user->fresh()->chats);
        $chatsService->delete($chat->id);
        $this->assertCount(0, $user->fresh()->chats);
    }
}
