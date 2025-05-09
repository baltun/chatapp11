<?php

namespace Tests\Feature\Messages;

use App\Models\Chat;
use App\Models\User;
use App\Services\Messages\DTO\MessageCreateDto;
use App\Models\Message;
use App\Services\Messages\MessagesService;
use Database\Seeders\ChatsSeeder;
use Database\Seeders\MessagesSeeder;
use Database\Seeders\UsersSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MessagesServiceTest extends TestCase
{
    use RefreshDatabase;

    public $startMessageTest;

    const TEST_MESSAGE_TEXT_1 = 'this test message text';

    public function setUp(): void
    {
        parent::setUp();
        $this->seed([
            UsersSeeder::class,
            ChatsSeeder::class,
            MessagesSeeder::class,
        ]);

        $this->actingAs(User::find(1));

        $this->startMessageTest = microtime(true);
    }

    public function test_message_create(): void
    {
        $chat = Chat::find(1);
        $user =$chat->participants()->first();
        $messagesService = $this->app->make(MessagesService::class);

        $dto = new MessageCreateDto([
            'text' => self::TEST_MESSAGE_TEXT_1,
        ]);

        $message = $messagesService->create($dto, $user->id, $chat->id);

        $this->assertNotEmpty($message);
        $this->assertInstanceOf(Message::class, $message);
        $this->assertEquals($message->text, self::TEST_MESSAGE_TEXT_1);
    }

    public function test_messages_list()
    {
        $messagesService = $this->app->make(MessagesService::class);

        $chat = Chat::find(1);
        $currentUser = auth()->user();

        $messages = $messagesService->list($currentUser->id, $chat->id);
        $this->assertNotEmpty($messages);
        $this->assertInstanceOf(Message::class, $messages[0]);
        $this->assertEquals($messages[0]->text, MessagesSeeder::MESSAGE_TEXT_1);
        $this->assertEquals($messages[0]->author_id, 1);
        $this->assertEquals($messages[0]->chat_id, 1);
    }

    public function tearDown(): void
    {
        parent::tearDown();

        $endMessageTest = microtime(true);
        $duration = $endMessageTest - $this->startMessageTest;
        echo "время:" . $duration;

    }
}
