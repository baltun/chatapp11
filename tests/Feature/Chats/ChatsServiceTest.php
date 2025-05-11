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

        $this->actingAs(User::find(1));
    }

    public function test_get_all_chats_success(): void
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

    public static function chatCreateSuccessProvider()
    {
        return [
            [[1, 2], 'chat1'],
            [[1, 3], 'personal'],
            [[1, 2, 3], 'created_chat_name'],
            [[4], 'personal'],
        ];
    }

    #[DataProvider('chatCreateSuccessProvider')]
    public function test_create_chat_success(array $userIds, string $expectedSlug): void
    {
        $chatCreateDto = new ChatCreateDTO([
            'slug' => 'created_chat_name',
            'userIds' => $userIds,
            'options' => [],
        ]);

        $chatsService = resolve(ChatsService::class);
        $chat = $chatsService->createOrGet($chatCreateDto);

        $this->assertEquals($chat->slug, $expectedSlug);
    }

    public static function chatCreateFailProvider()
    {
        return [
            [[2, 3], 'created_chat_name'],
        ];
    }

    #[DataProvider('chatCreateFailProvider')]
    public function test_create_chat_fail_not_participant(array $userIds, string $expectedSlug): void
    {
        $chatCreateDto = new ChatCreateDTO([
            'slug' => 'created_chat_name',
            'userIds' => $userIds,
            'options' => [],
        ]);

        $this->expectException(\App\Exceptions\AppLogicException::class);
        $this->expectExceptionMessage('You can not create a chat where you not a participant');
        $chatsService = resolve(ChatsService::class);
        $chat = $chatsService->createOrGet($chatCreateDto);
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
